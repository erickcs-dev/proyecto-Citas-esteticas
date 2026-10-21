---
tags:
  - modelo
  - eloquent
  - entidad
  - horarios
padre: "[[03 - Modelo de Datos y Esquema ER]]"
---

# ⏰ Entidad Eloquent: BusinessHour

- **Archivo PHP**: `app/Models/BusinessHour.php`
- **Tabla en BD**: `business_hours`

---

## 📋 Atributos y Fillable
| Campo | Tipo | Descripción |
| :--- | :--- | :--- |
| `id` | `bigint unsigned` | Llave primaria |
| `business_id` | `bigint unsigned` | Llave foránea a `businesses` |
| `day_of_week` | `integer` | 0=Domingo, 1=Lunes, 2=Martes... 6=Sábado |
| `open_time` | `time` | Hora de inicio de atención (HH:MM:SS) |
| `close_time` | `time` | Hora de fin de atención (HH:MM:SS) |
| `is_closed` | `boolean` | Bandera si el salón permanece cerrado ese día |

---

## 🔗 Relaciones Eloquent
- `business()`: `belongsTo([[entidades/Business|Business]])`.
