---
tags:
  - servicios
  - integraciones
  - apis
  - logica-dominio
padre: "[[00 - MOC - Indice Maestro]]"
---

# ⚙️ 05 - Servicios e Integraciones

Los servicios encapsulan lógica de negocio transversal en `app/Services/`. Esta capa evita controladores sobrecargados ("fat controllers") y modelos anémicos o sobrecargados de lógica procedimental.

---

## 🧩 1. `AppointmentAvailabilityService`
- **Ubicación**: `app/Services/AppointmentAvailabilityService.php`
- **Responsabilidad**: Calcular con precisión matemática los bloques de tiempo libres para agendar una cita.
- **Factores de Cálculo**:
  - Horario regular del negocio para el día de la semana (`BusinessHour`).
  - Duración del servicio solicitado (`Service->duration_minutes`).
  - Días feriados oficiales (`Holiday`).
  - Citas preexistentes en conflicto (`Appointment->where('status', '!=', 'cancelled')`).
- **Métodos Principales**:
  - `getAvailableSlots(Business $business, Service $service, Carbon $date): array`
  - `isSlotAvailable(Business $business, Service $service, Carbon $dateTime): bool`

---

## 💳 2. `AppointmentPaymentService`
- **Ubicación**: `app/Services/AppointmentPaymentService.php`
- **Responsabilidad**: Gestionar la pasarela de pagos y el ciclo financiero de la cita.
- **Acciones**:
  - Registro de métodos de pago (`cash`, `stripe`, `transfer`).
  - Actualización de `payment_status` (`pending`, `paid`, `refunded`).
  - Generación de referencias de pago únicas (`payment_reference`).

---

## 🌍 3. `HolidaySyncService`
- **Ubicación**: `app/Services/HolidaySyncService.php`
- **Responsabilidad**: Interoperabilidad y sincronización con APIs públicas de calendarios y feriados nacionales.
- **Conexión Externa**:
  - Consume servicios HTTP externos para traer festivos oficiales de Colombia/México (`country_code`).
  - Actualiza o inserta en la tabla `holidays` para que el sistema de reservas nunca permita agendar en días no laborables.
  - Implementa la visión de interoperabilidad de la **Guía 7 - Actividad 1**.

---

## 🔑 4. Integración de Autenticación Social (Google OAuth)
- **Controlador**: `app/Http/Controllers/Auth/GoogleAuthController.php`
- **Librería**: `laravel/socialite`
- **Mapeo de Atributos**:
  - `Google User ID` -> `users.google_id`
  - `Google Avatar` -> `users.avatar`
  - `Google Email` -> `users.email`
- Maneja usuarios nuevos vs recurrentes y enruta al proceso de onboarding si `profile_completed = false`.

---

## 🔗 Enlaces Relacionados
- MOC Principal: [[00 - MOC - Indice Maestro]]
- Controladores Consumidores: [[01 - Arquitectura y Capas del Sistema]]
- Módulo Distribuido: [[06 - Modulo Distribuido y SOAP]]
