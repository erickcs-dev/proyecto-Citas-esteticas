---
tags:
  - modelo
  - eloquent
  - entidad
  - resenas
padre: "[[03 - Modelo de Datos y Esquema ER]]"
---

# ⭐ Entidad Eloquent: BusinessReview

- **Archivo PHP**: `app/Models/BusinessReview.php`
- **Tabla en BD**: `business_reviews`

---

## 📋 Atributos y Fillable
| Campo | Tipo | Descripción |
| :--- | :--- | :--- |
| `id` | `bigint unsigned` | Llave primaria |
| `business_id` | `bigint unsigned` | Salón que recibe la calificación |
| `user_id` | `bigint unsigned` | Cliente que emitió la opinión |
| `appointment_id` | `bigint unsigned` | Cita completada previa (garantía de verificación) |
| `rating` | `integer` | Puntuación de 1 a 5 estrellas |
| `comment` | `text` | Opinión honesta del cliente |
| `images` | `json / array (nullable)` | Fotos del resultado estético subidas por el cliente |

---

## 🔗 Relaciones Eloquent
- `business()`: `belongsTo([[entidades/Business|Business]])`.
- `user()`: `belongsTo([[entidades/User|User]])`.
- `appointment()`: `belongsTo([[entidades/Appointment|Appointment]])`.
