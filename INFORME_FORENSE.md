# 📋 INFORME TÉCNICO Y FORENSE DE CODIFICACIÓN, TDD Y MANTENIMIENTO DE SOFTWARE

- **Institución:** Fundación de Estudios Superiores Comfanorte (FESC)
- **Programa:** Ingeniería de Software — Semestre II
- **Asignatura:** Codificación y Pruebas del Software
- **Unidad de Aprendizaje N.º 1:** Codificación de Software (Código: `FPESF-4.1-16`, Versión 3.4)
- **Proyecto Auditado e Intervenido:** `citas-app` (Plataforma de Gestión de Citas Estéticas y Pagos)
- **Autores:** Erick Cuevas y Camilo Ramírez
- **Fecha:** Octubre de 2026

---

## 1. Análisis Forense de Código Fuente y Estándares de Calidad (Actividad 1)

### 1.1. Contexto del Análisis Forense
Siguiendo los lineamientos de la **Guía de Aprendizaje N.º 1**, se realizó una auditoría forense sobre los módulos críticos de **facturación/pagos de anticipos**, **agendamiento de citas** y **gestión de archivos/servicios** de la plataforma `citas-app`. El propósito fue identificar vulnerabilidades de seguridad, defectos lógicos de negocio y desviaciones de estilo antes de su paso a producción, evaluando su impacto bajo el estándar **ISO/IEC 25010** y aplicando las guías de estilo **PSR-12** (equivalente en el ecosistema PHP/Laravel a **PEP 8** en Python) y **IEEE 829** para la documentación de pruebas.

### 1.2. Clasificación de Errores Detectados y Mitigados

| Categoría del Error | Hallazgo Forense en el Código Legado / Previo | Impacto en el Negocio | Característica ISO/IEC 25010 Afectada | Solución y Refactorización Aplicada |
| :--- | :--- | :--- | :--- | :--- |
| **Lógico / Regla de Negocio** | `AppointmentPaymentService::buildPaymentData()` calculaba el 50% de anticipo sin verificar si el precio del servicio era `<= 0`, permitiendo registrar valores negativos o nulos. | Pérdida financiera y corrupción de registros de transacciones en la base de datos. | **Adecuación Funcional** (*Exactitud Funcional*) y **Fiabilidad** (*Madurez*). | Validación defensiva que lanza `InvalidArgumentException` cuando `service_price <= 0` en [`AppointmentPaymentService.php`](app/Services/AppointmentPaymentService.php). |
| **Lógico / Reportería** | El cálculo de métricas financieras globales no segregaba las citas con estado `cancelled` de las citas activas. | Descuadre contable en los reportes gerenciales de ingresos esperados y anticipos recaudados. | **Adecuación Funcional** (*Exactitud en cálculos de reportería*). | Filtrado explícito mediante `$appointments->reject(...)` en [`Api\AppointmentController::exportReport()`](app/Http/Controllers/Api/AppointmentController.php). |
| **Seguridad (Inyección / Entrada Maliciosa)** | Ausencia de validador de payload transaccional ante correos mal formateados o referencias con caracteres de inyección (`'; DROP TABLE...`) y *Path Traversal* (`../../`) en sincronización de festivos. | Riesgo de inyección de comandos/consultas, contaminación de bitácoras y manipulación de rutas. | **Seguridad** (*Integridad*, *No repudio* y *Autenticidad*). | Método defensivo `validateTransactionPayload()` con `FILTER_VALIDATE_EMAIL` y regex `/^[A-Za-z0-9._-]{3,60}$/`, más validación `alpha\|size:2` en `country_code`. |
| **Seguridad (IDOR y Exposición de Datos)** | Riesgo de eliminación arbitraria de imágenes ajenas vía `remove_images[]` y persistencia accidental de datos de tarjeta (`card_number`, `cvv`) en la sesión `_old_input` tras un error de validación. | Borrado de activos de otros negocios y exposición de datos sensibles de pago (incumplimiento PCI/Habeas Data). | **Seguridad** (*Confidencialidad* y *Responsabilidad*). | Verificación de pertenencia en `ServiceController::update()`, exclusión de campos sensibles con `sensitivePaymentFields()` y cabeceras en [`SecurityHeadersMiddleware.php`](app/Http/Middleware/SecurityHeadersMiddleware.php). |
| **Sintáctico y de Estilo (Linter)** | 34 archivos presentaban desviaciones del estándar **PSR-12 / PEP 8** (importaciones desordenadas, ausencia de espacio tras operador `!`, paréntesis innecesarios y saltos de línea al final de archivo). | Dificultad de lectura, mayor deuda técnica y fricción en revisiones de código por pares. | **Mantenibilidad** (*Analizabilidad* y *Modificabilidad*). | Refactorización automatizada y verificación estricta con el analizador estático **Laravel Pint** (`vendor/bin/pint --test`). |

---

## 2. Codificación Defensiva y Metodología TDD: Red - Green - Refactor (Actividad 2)

Para el fortalecimiento del módulo de **Gestión de Pagos y Validación de Transacciones** ([`app/Services/AppointmentPaymentService.php`](app/Services/AppointmentPaymentService.php)), se aplicó estrictamente el ciclo **TDD (*Test-Driven Development*)** utilizando **PHPUnit 11.5** (herramienta de la familia **xUnit**), garantizando que ninguna línea de código defensivo en producción fuera escrita sin una prueba unitaria fallida previa.

### 2.1. Fase 1: *Red Phase* (Pruebas Iniciales Fallidas)
Se diseñaron en [`tests/Unit/AppointmentPaymentServiceTest.php`](tests/Unit/AppointmentPaymentServiceTest.php) cinco casos de prueba unitarios orientados a comprobar el rechazo de montos negativos o cero, correos mal formateados e intentos de inyección en la referencia de pago:

```text
FAIL  Tests\Unit\AppointmentPaymentServiceTest
✓ build payment data calculates advance correctly                                0.03s
✓ build payment data preserves custom payment status
⨯ build payment data rejects negative or zero service price                      0.95s
⨯ validate defensive transaction accepts valid payload
⨯ validate defensive transaction rejects malformed email
⨯ validate defensive transaction rejects non positive amount
⨯ validate defensive transaction rejects disallowed characters in reference

Tests: 5 failed, 2 passed (10 assertions)
```

### 2.2. Fase 2: *Green Phase* (Implementación Mínima Defensiva)
Se implementó en [`app/Services/AppointmentPaymentService.php`](app/Services/AppointmentPaymentService.php) la validación por contrato (*Design by Contract*) y programación defensiva:

```php
public function buildPaymentData(Service $service, ?string $paymentStatus = null): array
{
    $servicePrice = round((float) $service->price, 2);

    if ($servicePrice <= 0) {
        throw new InvalidArgumentException('El precio del servicio debe ser mayor a cero.');
    }

    $advancePercentage = 50.00;
    $advanceAmount = round($servicePrice * ($advancePercentage / 100), 2);

    return [
        'service_price' => $servicePrice,
        'advance_percentage' => $advancePercentage,
        'advance_amount' => $advanceAmount,
        'payment_status' => $paymentStatus ?: Appointment::PAYMENT_STATUS_PENDING_ADVANCE,
    ];
}
```

Adicionalmente, el método `validateTransactionPayload(string $email, float $amount, string $reference)` normaliza y valida el correo electrónico (`filter_var(..., FILTER_VALIDATE_EMAIL)`), exige `$amount > 0` y valida la referencia contra la lista blanca `SAFE_REFERENCE_PATTERN = '/^[A-Za-z0-9._-]{3,60}$/'`.

### 2.3. Fase 3: *Refactor Phase* y Cumplimiento de Linter
Se refactorizó el código eliminando duplicación, extrayendo constantes descriptivas, tipando estrictamente parámetros y retornos (`declare/typehints`) y validando el cumplimiento del estándar de estilo con **Laravel Pint**:

```text
PASS  Tests\Unit\AppointmentPaymentServiceTest
✓ build payment data calculates advance correctly                                0.04s
✓ build payment data preserves custom payment status
✓ build payment data rejects negative or zero service price                      0.03s
✓ validate defensive transaction accepts valid payload
✓ validate defensive transaction rejects malformed email
✓ validate defensive transaction rejects non positive amount
✓ validate defensive transaction rejects disallowed characters in reference

{"tool":"pint","result":"passed"}
```

---

## 3. Mantenimiento de Software y Pruebas de Regresión (Actividad 3)

### 3.1. Diferenciación Conceptual y Aplicación en el Proyecto

1. **Mantenimiento Correctivo:**
   - *Definición:* Modificación reactiva realizada sobre el software para localizar y eliminar defectos de programación, lógica o seguridad descubiertos en las pruebas o en operación.
   - *Aplicación en `citas-app`:* Corrección del cálculo de anticipos frente a precios negativos/cero en `AppointmentPaymentService`, mitigación de borrado IDOR de imágenes en `ServiceController` y corrección del formato de horas con segundos (`H:i:s` a `H:i`) en `AppointmentAvailabilityService::normalizeTime()`.
2. **Mantenimiento Adaptativo:**
   - *Definición:* Modificación del sistema para asegurar que continúe operando correctamente ante cambios en el entorno externo (APIs de terceros, motores de base de datos, sistemas operativos o normativas).
   - *Aplicación en `citas-app`:* Implementación de [`HolidaySyncService.php`](app/Services/HolidaySyncService.php) que consume la API externa `https://date.nager.at/api/v3/PublicHolidays` y adapta sus fallas mediante respuestas controladas HTTP `502 Bad Gateway` (verificado en [`HolidaySyncTest.php`](tests/Feature/HolidaySyncTest.php)), así como soporte transparente entre SQLite (pruebas en memoria/CI) y MySQL/PostgreSQL.
3. **Mantenimiento Evolutivo (Perfectivo/Extensión):**
   - *Definición:* Adición de nuevas funcionalidades o capacidades solicitadas por los usuarios o el negocio para ampliar el valor del producto sin introducir regresiones en los módulos existentes.
   - *Aplicación en `citas-app`:* Creación del módulo de **Reportería Exportable en JSON** (`GET /api/appointments/export`) en [`Api\AppointmentController::exportReport()`](app/Http/Controllers/Api/AppointmentController.php), el cual genera un consolidado estructurado en JSON excluyendo las citas canceladas de la sumatoria financiera (`total_service_amount` y `total_advance_amount`), respaldado por la prueba de regresión `test_authenticated_user_can_export_appointments_json_report_excluding_cancelled_from_totals` en [`AppointmentManagementTest.php`](tests/Feature/AppointmentManagementTest.php).

### 3.2. Trazabilidad en Git con Ramas y *Conventional Commits*
El flujo de intervención se ejecutó aislando cada tipo de mantenimiento en ramas dedicadas antes de fusionarlas a `main` bajo la convención **Conventional Commits**:

- Rama correctiva: `fix/validacion-defensiva-pagos` → Commit: `fix(pagos): aplicada codificacion defensiva y TDD contra montos negativos, correos invalidos e inyecciones`
- Rama evolutiva: `feature/exportacion-json` → Commit: `feat(citas): agregada funcion de exportacion de reportes en JSON excluyendo citas canceladas`

```text
*   298643a (HEAD -> main) merge: integrar mantenimiento evolutivo feature/exportacion-json
|\
| * 8f014a3 (feature/exportacion-json) feat(citas): agregada funcion de exportacion de reportes en JSON excluyendo citas canceladas
|/
*   84e6f98 merge: integrar mantenimiento correctivo fix/validacion-defensiva-pagos
|\
| * 631a8c8 (fix/validacion-defensiva-pagos) fix(pagos): aplicada codificacion defensiva y TDD contra montos negativos, correos invalidos e inyecciones
|/
* edba3d9 chore: estructura base del sistema citas-app y estandarizacion PSR-12 con Laravel Pint
```

---

## 4. Evidencia de Ejecución de Linters y Pruebas de Regresión (xUnit)

### 4.1. Resultado de la Suite Completa de Pruebas Automatizadas (PHPUnit)
Comando ejecutado: `.\artisan.cmd test`

```text
   PASS  Tests\Unit\AppointmentPaymentServiceTest
  ✓ build payment data calculates advance correctly                                                              0.04s
  ✓ build payment data preserves custom payment status
  ✓ build payment data rejects negative or zero service price                                                    0.03s
  ✓ validate defensive transaction accepts valid payload
  ✓ validate defensive transaction rejects malformed email
  ✓ validate defensive transaction rejects non positive amount
  ✓ validate defensive transaction rejects disallowed characters in reference

   PASS  Tests\Unit\AppointmentTest
  ✓ appointment returns correct statuses                                                                         0.02s
  ✓ is closed returns true for completed or cancelled

   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\AppointmentManagementTest
  ✓ client can create appointment with automatic end time and redirect to payment                                0.67s
  ✓ client can create appointment at exact opening time                                                          0.14s
  ✓ invalid start time returns validation error                                                                  0.14s
  ✓ business user cannot create appointment for another business                                                 0.14s
  ✓ client can pay advance and auto confirm the appointment                                                      0.13s
  ✓ payment validation does not flash sensitive card data                                                        0.13s
  ✓ appointment cannot overlap another appointment                                                               0.12s
  ✓ business user can mark payment as paid                                                                       0.13s
  ✓ business user cannot move appointment to another business                                                    0.14s
  ✓ client can update appointment even if time arrives with seconds                                              0.14s
  ✓ authenticated user can export appointments json report excluding cancelled from totals                       0.15s

   PASS  Tests\Feature\AuthFlowTest
  ✓ guest can register client and is redirected to profile onboarding                                            0.15s
  ✓ registration fails without accepting terms                                                                   0.12s
  ✓ user can login with valid credentials                                                                        0.12s
  ✓ incomplete user is redirected to profile onboarding after login                                              0.14s
  ✓ client with pending business request still logs in to dashboard                                              0.12s
  ✓ google redirect uses socialite                                                                               0.13s
  ✓ google callback creates a client and redirects to onboarding                                                 0.13s
  ✓ admin can approve a pending business request                                                                 0.13s
  ✓ login is rate limited after repeated failures                                                                1.16s

   PASS  Tests\Feature\BusinessManagementTest
  ✓ business user can create a business                                                                          0.15s
  ✓ client user cannot create a business                                                                         0.13s
  ✓ public business page is available by slug                                                                    0.17s

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                                                                0.14s

   PASS  Tests\Feature\HolidaySyncTest
  ✓ admin can sync holidays from external api                                                                    0.16s
  ✓ api returns error when external service fails                                                                0.12s

   PASS  Tests\Feature\SecurityHardeningTest
  ✓ service update cannot delete images outside own service                                                      0.17s
  ✓ client cannot access business management area                                                                0.14s
  ✓ holiday sync api validates country code format                                                               0.12s
  ✓ security headers are present on web and api                                                                  0.14s

  Tests:    40 passed (144 assertions)
  Duration: 6.05s
```

### 4.2. Resultado del Analizador Estático de Estilo y Calidad (Laravel Pint)
Comando ejecutado: `.\php.cmd vendor\bin\pint --test`

```json
{"tool":"pint","result":"passed"}
```

---

## 5. Declaración Ética de Uso de la IA y Referencias Bibliográficas (Normas APA 7)

### 5.1. Declaración Ética
De acuerdo con el numeral 8 de la Guía de Aprendizaje de la FESC, la documentación, refactorización y diseño de pruebas de este repositorio se rigen por los principios éticos de **veracidad, transparencia y responsabilidad** en el manejo de la información. Las herramientas de asistencia de codificación e inteligencia artificial se emplearon como apoyo para la verificación estática, estructuración de pruebas unitarias bajo el ciclo TDD y validación del estándar PSR-12/ISO 25010, siendo cada cambio verificado empíricamente contra el ejecutor de pruebas PHPUnit.

### 5.2. Referencias Bibliográficas
- Alonso Amo, F., Martínez Normand, L., & Segovia Pérez, J. (2005). *Introducción a la Ingeniería del Software: modelos de desarrollo de programas*. Delta Publicaciones. https://elibro.net/es/ereader/fesc/170188
- Echeverri, J., Aristizábal, M., & González, L. (2013). *Reflexiones sobre ingeniería de requisitos y pruebas de software*. Corporación Universitaria Remington. https://elibro.net/es/lc/fesc/titulos/68913
- GitHub, Inc. (2024). *Git Documentation*. https://git-scm.com/doc
- International Organization for Standardization. (2011). *ISO/IEC 25010:2011 Systems and software engineering — Systems and software Quality Requirements and Evaluation (SQuaRE) — System and software quality models*. ISO.
- Pantaleo, G. (2015). *Ingeniería de Software*. Alfaomega.
- PHP-FIG. (2019). *PSR-12: Extended Coding Style*. PHP Framework Interoperability Group. https://www.php-fig.org/psr/psr-12/
- Pressman, R. S. (1998). *Ingeniería del software: Un enfoque práctico*. McGraw-Hill.
- Python Software Foundation. (2024). *PEP 8 – Style Guide for Python Code*. https://peps.python.org/pep-0008/
