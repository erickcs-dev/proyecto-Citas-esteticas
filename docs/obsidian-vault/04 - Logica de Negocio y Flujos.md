---
tags:
  - logica-de-negocio
  - flujos
  - maquina-de-estados
  - citas
padre: "[[00 - MOC - Indice Maestro]]"
---

# 🔄 04 - Lógica de Negocio y Flujos del Sistema

Este documento describe los flujos operativos clave de **citas-app**, incluyendo la máquina de estados de las citas, el cálculo de disponibilidad de agenda y el proceso de autenticación e inducción.

---

## 📅 1. Ciclo de Vida y Máquina de Estados de una Cita

```mermaid
stateDiagram-v2
    [*] --> Pendiente : Cliente solicita cita (store)
    
    Pendiente --> Confirmada : Dueño o Admin aprueba cita
    Pendiente --> Cancelada : Cliente o Dueño rechaza/cancela
    
    Confirmada --> En_Pago : Requiere pago previo
    Confirmada --> Completada : Servicio prestado con éxito
    Confirmada --> Cancelada : Cancelación con aviso
    
    En_Pago --> Confirmada : Pago completado (paid)
    En_Pago --> Cancelada : Pago vencido o rechazado
    
    Completada --> Reseña_Habilitada : Permite escribir reseña
    Reseña_Habilitada --> [*]
    Cancelada --> [*]
```

### Reglas de Negocio en la Transición de Estados
1. **Creación**: Al registrar una cita, el sistema valida que no exista solapamiento horario con otra cita confirmada en el mismo salón para el mismo horario (`scheduled_at` + `duration_minutes`).
2. **Validación de Días Inhábiles**: Si la fecha solicitada coincide con un registro en la tabla `holidays`, el formulario bloquea la selección.
3. **Reseñas Verificadas**: Únicamente las citas en estado `completed` permiten disparar la ruta `public.businesses.reviews.store`. Esto previene reseñas fraudulentas.

---

## ⏱️ 2. Flujo de Cálculo de Disponibilidad

El servicio [[05 - Servicios e Integraciones#AppointmentAvailabilityService|AppointmentAvailabilityService]] ejecuta la siguiente lógica para generar las horas disponibles:

```mermaid
sequenceDiagram
    autonumber
    actor Cliente
    participant Frontend as Selector de Fecha/Hora
    participant Controller as AvailabilityController
    participant Service as AppointmentAvailabilityService
    participant DB as Base de Datos

    Cliente->>Frontend: Selecciona fecha deseada (Ej: 2026-10-15)
    Frontend->>Controller: GET /api/businesses/{id}/availability?date=...&service_id=...
    Controller->>Service: getAvailableSlots(business, service, date)
    
    Service->>DB: Consultar si la fecha es Holiday
    alt Es Feriado
        DB-->>Service: Registro encontrado
        Service-->>Controller: [] (Día no laborable)
    else No es Feriado
        Service->>DB: Consultar BusinessHour para el dayOfWeek
        alt Cerrado ese día
            Service-->>Controller: [] (Cerrado)
        else Abierto
            Service->>DB: Consultar Appointments existentes para esa fecha
            Service->>Service: Generar intervalos desde open_time hasta close_time
            Service->>Service: Restar slots ocupados por citas pendientes/confirmadas
            Service-->>Controller: [09:00, 09:30, 10:00, 11:30, ...]
        end
    end
    Controller-->>Frontend: JSON con slots disponibles
    Frontend-->>Cliente: Renderiza botones interactivos de horarios
```

---

## 🔐 3. Flujo de Autenticación, Google OAuth y Onboarding

```mermaid
sequenceDiagram
    autonumber
    actor Usuario
    participant Navegador
    participant App as Citas-App (Socialite)
    participant Google as Google Identity Server
    participant DB as Base de Datos

    Usuario->>Navegador: Clic en "Continuar con Google"
    Navegador->>App: GET /auth/google/redirect
    App->>Google: Redirección con Client ID y Scopes
    Google-->>Usuario: Solicita consentimiento de cuenta
    Usuario->>Google: Acepta permisos
    Google->>App: Callback con Token de Acceso
    App->>Google: Solicita perfil (nombre, email, avatar, google_id)
    Google-->>App: Datos del perfil
    App->>DB: Buscar usuario por email o google_id
    alt Usuario nuevo
        App->>DB: Crear usuario con profile_completed = false
        App->>Navegador: Redirigir a /onboarding/profile
        Usuario->>Navegador: Ingresa celular y confirma rol
        Navegador->>App: PUT /onboarding/profile
        App->>DB: profile_completed = true
    else Usuario existente
        App->>Navegador: Redirigir según rol (Dashboard o Catálogo)
    end
```

---

## 🔗 Enlaces Relacionados
- MOC Principal: [[00 - MOC - Indice Maestro]]
- Detalle de Entidad Cita: [[entidades/Appointment]]
- Endpoints: [[02 - Mapa de Rutas y Endpoints]]
