---
tags:
  - modelo
  - eloquent
  - entidad
  - feriados
padre: "[[03 - Modelo de Datos y Esquema ER]]"
---

# 🏖️ Entidad Eloquent: Holiday

- **Archivo PHP**: `app/Models/Holiday.php`
- **Tabla en BD**: `holidays`

---

## 📋 Atributos y Fillable
| Campo | Tipo | Descripción |
| :--- | :--- | :--- |
| `id` | `bigint unsigned` | Llave primaria |
| `holiday_date` | `date` | Fecha del día festivo (YYYY-MM-DD) |
| `name` | `string` | Nombre o festividad (Ej: "Año Nuevo", "Navidad") |
| `country_code` | `string` | Código de país ISO (Ej: `CO`, `MX`) |

---

## 🛠️ Interoperabilidad
Alimentado periódicamente desde [[05 - Servicios e Integraciones#HolidaySyncService|HolidaySyncService]]. El motor de disponibilidad bloquea cualquier turno solicitado en una fecha registrada en este modelo.
