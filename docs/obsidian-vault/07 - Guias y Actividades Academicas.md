---
tags:
  - academia
  - guias
  - cliente-servidor
  - rubrica
padre: "[[00 - MOC - Indice Maestro]]"
---

# 🎓 07 - Guías y Actividades Académicas (Cliente-Servidor)

Este documento centraliza la correspondencia entre los requerimientos pedagógicos de la materia **Cliente-Servidor** (desarrollada por Erick Cuevas y Camilo Ramirez) y su implementación concreta en el código de **citas-app**.

---

## 📌 1. Guía 1 - Actividad 3: Análisis de Capas

> **Requisito**: Evidenciar la separación estricta de responsabilidades entre la entrada de peticiones, la orquestación lógica, las reglas de negocio y la persistencia de datos.

- **Implementación en el Código**:
  - **Capa de Entrada (HTTP)**: `routes/web.php` y `routes/api.php`. Separan los verbos y rutas de la lógica de procesamiento.
  - **Capa de Control**: `app/Http/Controllers/`. Los controladores orquestan la petición sin mezclar sentencias SQL directas ni maquetación HTML pesada.
  - **Capa de Servicios**: `app/Services/`. Aloja lógica especializada (ej. `AppointmentAvailabilityService` y `AppointmentPaymentService`).
  - **Capa de Persistencia**: `app/Models/` y `database/migrations/`. Eloquent ORM encapsula el acceso a la base de datos relacional.
  - **Capa de Presentación**: `resources/views/`. Vistas Blade desacopladas de las consultas de base de datos.
- **Documento Detallado**: [[01 - Arquitectura y Capas del Sistema]]

---

## 📌 2. Guía 2 - Actividad 1: Interoperabilidad

> **Requisito**: Implementar mecanismos que permitan a clientes externos o heterogéneos intercambiar información de forma estructurada con el servidor.

- **Implementación en el Código**:
  - **API REST JSON**: En `routes/api.php`, exponiendo catálogos de negocios, servicios y horarios en formato estándar application/json.
  - **Módulo Distribuido SOAP**: En `distributed/`, con servicios web SOAP mediante `soap-service.php`, validación de esquemas XML (`schemas/`) y consumidor `client.php`.
- **Documento Detallado**: [[02 - Mapa de Rutas y Endpoints]] y [[06 - Modulo Distribuido y SOAP]]

---

## 📌 3. Guía 7 - Actividad 1: Interoperabilidad Web

> **Requisito**: Exposición y sincronización de datos web para clientes HTTP y consumo de información externa.

- **Implementación en el Código**:
  - **Sincronización de Feriados**: `HolidaySyncService.php` y endpoint `POST /api/holidays/sync` y `POST /holidays/sync`, que consume servicios de terceros para evitar citas en festivos.
  - **Disponibilidad en Tiempo Real**: Endpoint `GET /api/businesses/{business}/availability` que permite a aplicaciones externas (móviles, widgets o SPAs) calcular la agenda libre en vivo.
- **Documento Detallado**: [[05 - Servicios e Integraciones]]

---

## 📌 4. Guía Unidad 1 (Codificación y Pruebas del Software - FESC)

> **Requisito**: Evidenciar análisis forense de errores bajo norma ISO/IEC 25010, codificación defensiva mediante TDD (*Red-Green-Refactor*) con herramientas tipo xUnit, cumplimiento de linter y mantenimiento correctivo/evolutivo con pruebas de regresión y *Conventional Commits*.

- **Implementación en el Código**:
  - **Análisis Forense y Seguridad (Actividad 1)**: Mitigación de IDOR, *Path Traversal*, cabeceras de seguridad y ocultamiento de datos sensibles de tarjetas verificados en `tests/Feature/SecurityHardeningTest.php` y documentados en `INFORME_FORENSE.md`.
  - **Codificación Defensiva y TDD (Actividad 2)**: Validación de precios mayores a cero, formato de correo electrónico y bloqueo de inyecciones en referencias dentro de `app/Services/AppointmentPaymentService.php`, cubierto con pruebas unitarias puras en `tests/Unit/AppointmentPaymentServiceTest.php` y cumplimiento 100% del linter **Laravel Pint** (PSR-12).
  - **Mantenimiento Correctivo, Evolutivo y Regresión (Actividad 3)**: Extensión de reportería exportable en JSON (`GET /api/appointments/export`) en `app/Http/Controllers/Api/AppointmentController.php` excluyendo citas canceladas del total financiero, validada con pruebas de regresión en `tests/Feature/AppointmentManagementTest.php` y trazabilidad en Git (`fix/validacion-defensiva-pagos` y `feature/exportacion-json`).

---

## 🔗 Enlaces Relacionados
- MOC Principal: [[00 - MOC - Indice Maestro]]
- Arquitectura y Capas: [[01 - Arquitectura y Capas del Sistema]]
- Decisiones Técnicas: [[08 - Bitacora de Decisiones (ADR) y Memoria]]
