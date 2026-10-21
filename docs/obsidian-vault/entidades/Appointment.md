---
tags:
  - modelo
  - eloquent
  - entidad
  - citas
padre: "[[03 - Modelo de Datos y Esquema ER]]"
---

# 📅 Entidad Eloquent: Appointment

- **Archivo PHP**: `app/Models/Appointment.php`
- **Tabla en BD**: `appointments`

---

## 📋 Atributos y Fillable
| Campo | Tipo | Descripción |
| :--- | :--- | :--- |
| `id` | `bigint unsigned` | Llave primaria |
| `user_id` | `bigint unsigned` | Cliente que reservó la cita |
| `business_id` | `bigint unsigned` | Negocio donde se realizará el servicio |
| `service_id` | `bigint unsigned` | Servicio agendado |
| `scheduled_at` | `datetime` | Fecha y hora fijada para la cita |
| `status` | `string` | `pending`, `confirmed`, `completed`, `cancelled` |
| `payment_status` | `string` | `pending`, `paid`, `refunded` |
| `payment_method` | `string (nullable)` | Pasarela o medio (`cash`, `stripe`, etc.) |
| `payment_reference` | `string (nullable)` | Identificador de transacción |
| `client_notes` | `text (nullable)` | Observaciones o solicitudes del cliente |

---

## 🔗 Relaciones Eloquent
- `user()`: `belongsTo([[entidades/User|User]])` (Cliente).
- `business()`: `belongsTo([[entidades/Business|Business]])` (Salón).
- `service()`: `belongsTo([[entidades/Service|Service]])` (Tratamiento).
- `review()`: `hasOne([[entidades/BusinessReview|BusinessReview]])` (Reseña asociada).

---

## ⚙️ Reglas de Transición
Ver diagrama completo de estados y transiciones en [[04 - Logica de Negocio y Flujos]].
