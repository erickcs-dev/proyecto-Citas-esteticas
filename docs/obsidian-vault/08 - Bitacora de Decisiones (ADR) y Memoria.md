---
tags:
  - adr
  - memoria
  - decisiones
  - bitacora
padre: "[[00 - MOC - Indice Maestro]]"
---

# 🧠 08 - Bitácora de Decisiones (ADR) y Memoria del Proyecto

Este documento actúa como el **diario de abordo y memoria técnica viva** del proyecto. Cada decisión de arquitectura, refactorización o cambio de diseño relevante debe registrarse aquí por el **Agente de Memoria y Mapeo**.

---

## 📋 Registro Cronológico de Decisiones Arquitectónicas (ADRs)

### ADR-001: Adopción de Laravel 11 y PHP 8.3+
- **Fecha**: 2026-03-15
- **Estado**: Aprobado e Implementado
- **Contexto**: Se necesitaba un framework robusto con routing expresivo, ORM maduro y sistema de migraciones ágil.
- **Decisión**: Utilizar Laravel 11 configurando el motor de plantillas Blade y Vite para el empaquetado de assets.
- **Consecuencia**: Ecosistema estandarizado, excelente tooling y compatibilidad total con librerías oficiales como Socialite.

### ADR-002: Inyección Dinámica de Marca por Negocio (`--primary-color`)
- **Fecha**: 2026-05-25
- **Estado**: Aprobado e Implementado
- **Contexto**: Los dueños de salones necesitan reflejar la personalidad de su marca, pero sin romper la usabilidad ni la consistencia visual de la plataforma.
- **Decisión**: Almacenar un color hexadecimal en `businesses.primary_color` e inyectarlo dinámicamente como variable CSS `--primary-color` exclusivamente en las vistas públicas del negocio. El panel administrativo y el catálogo global conservan la paleta neutral *warm-minimal*.
- **Consecuencia**: Cero contaminación visual en interfaces administrativas y personalización boutique para cada salón.

### ADR-003: Autenticación Híbrida con Google OAuth y Onboarding en Dos Fases
- **Fecha**: 2026-05-26
- **Estado**: Aprobado e Implementado
- **Contexto**: Facilitar el registro rápido sin fricción pero asegurando que se capture el teléfono de contacto necesario para confirmar citas.
- **Decisión**: Implementar `laravel/socialite` con Google. Si el usuario es nuevo, se crea con `profile_completed = false` y el middleware `profile.completed` lo intercepta para obligar a registrar su información básica antes de reservar o administrar.
- **Consecuencia**: Alta tasa de conversión de clientes y datos de contacto fiables.

### ADR-004: Reseñas Estrictamente Verificadas
- **Fecha**: 2026-05-27
- **Estado**: Aprobado e Implementado
- **Contexto**: Evitar spam de valoraciones falsas que minen la confianza en los salones.
- **Decisión**: La tabla `business_reviews` requiere obligatoriamente una llave foránea `appointment_id` y solo se habilita el formulario de reseña para citas con estado `completed`.
- **Consecuencia**: Reputación 100% verídica basada en servicios efectivamente consumidos.

### ADR-005: Bóveda de Mapeo en Obsidian y Agente Autónomo de Memoria
- **Fecha**: 2026-09-22
- **Estado**: Aprobado e Implementado
- **Contexto**: El proyecto creció en complejidad (módulo web, API REST, base de datos relacional y módulo distribuido SOAP). Se requería una herramienta de mapeo visual hiperconectada y un agente de IA que preservara la memoria de cambios.
- **Decisión**: Configurar una bóveda nativa de Obsidian en `docs/obsidian-vault/`, un lienzo `Arquitectura-General.canvas` y la skill `.agents/skills/project-memory-mapper`.
- **Consecuencia**: Documentación visual, navegable y actualizada automáticamente mediante scripts y protocolos de memoria.

---

## 📌 Estado Actual y Próximos Pasos (Backlog de Memoria)
- [x] Mapeo de rutas Web y API en Obsidian.
- [x] Mapeo de esquema de base de datos relacional y migraciones.
- [x] Diagrama interactivo Obsidian Canvas.
- [x] Script de sincronización automática `sync-obsidian-map.ps1`.
- [ ] Integración de notificaciones automáticas por correo/SMS cuando una cita cambia de estado.
- [ ] Implementación de pasarela de pago real (Stripe Elements) sobre el servicio `AppointmentPaymentService`.
