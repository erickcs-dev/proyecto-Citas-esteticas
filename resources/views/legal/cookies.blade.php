@extends('layouts.app')

@section('title', 'Política de Cookies')

@section('content')
<div class="legal-container" style="max-width: 860px; margin: 2rem auto; padding: 2rem; background: var(--surface, #ffffff); border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); line-height: 1.7;">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-primary, #111827);">Política de Cookies</h1>
    <p style="color: var(--text-muted, #6b7280); font-size: 0.9rem; margin-bottom: 2rem;">Última actualización: {{ date('d/m/Y') }} · Directiva ePrivacy y estándares internacionales</p>

    <section style="margin-bottom: 1.75rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 0.5rem;">1. ¿Qué son las Cookies?</h2>
        <p>Una cookie es un pequeño archivo de texto que los sitios web almacenan en su navegador o dispositivo para recordar información sobre su visita, mantener su sesión activa y mejorar su experiencia de navegación.</p>
    </section>

    <section style="margin-bottom: 1.75rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 0.5rem;">2. Tipos de Cookies que Utilizamos</h2>
        <div style="margin-top: 1rem;">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #374151;">A. Cookies Técnicas y Esenciales (Obligatorias)</h3>
            <p>Son imprescindibles para el correcto funcionamiento de la plataforma y no pueden ser desactivadas:</p>
            <ul style="list-style: disc; padding-left: 1.5rem; margin-top: 0.25rem;">
                <li><code>laravel_session</code>: Mantiene identificada la sesión activa del usuario autenticado.</li>
                <li><code>XSRF-TOKEN</code>: Protege contra ataques de falsificación de peticiones en sitios cruzados (CSRF).</li>
                <li><code>cookie_consent</code>: Almacena la preferencia de consentimiento del usuario sobre las cookies.</li>
            </ul>
        </div>

        <div style="margin-top: 1rem;">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #374151;">B. Cookies de Rendimiento y Análisis (Opcionales)</h3>
            <p>Nos ayudan a entender de forma anónima cómo interactúan los usuarios con la plataforma (métricas de visitas, páginas más vistas y tiempos de respuesta) para optimizar el rendimiento técnico.</p>
        </div>
    </section>

    <section style="margin-bottom: 1.75rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 0.5rem;">3. ¿Cómo Gestionar o Eliminar las Cookies?</h2>
        <p>Usted puede revocar o modificar su consentimiento en cualquier momento limpiando las cookies de su navegador o configurando las preferencias de su explorador web (Chrome, Edge, Firefox, Safari) para bloquear o alertar sobre la instalación de cookies.</p>
    </section>
</div>
@endsection
