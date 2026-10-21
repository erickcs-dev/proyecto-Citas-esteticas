---
tags:
  - modelo
  - eloquent
  - entidad
  - catalogo
padre: "[[03 - Modelo de Datos y Esquema ER]]"
---

# 💅 Entidad Eloquent: Service

- **Archivo PHP**: `app/Models/Service.php`
- **Tabla en BD**: `services`

---

## 📋 Atributos y Fillable
| Campo | Tipo | Descripción |
| :--- | :--- | :--- |
| `id` | `bigint unsigned` | Llave primaria |
| `business_id` | `bigint unsigned` | Negocio al que pertenece el servicio |
| `name` | `string` | Nombre del servicio (ej. "Manicura Rusa") |
| `description` | `text` | Detalle del procedimiento y beneficios |
| `price` | `decimal(10,2)` | Precio de venta al público |
| `duration_minutes`| `integer` | Tiempo estimado en minutos (bloque de agenda) |
| `gallery_images` | `json / array` | Lista de imágenes de trabajos previos |
| `is_active` | `boolean` | Indica si está disponible para reservas |

---

## 🔗 Relaciones Eloquent
- `business()`: Retorna `belongsTo([[entidades/Business|Business]])`.
- `appointments()`: Retorna `hasMany([[entidades/Appointment|Appointment]])`.
