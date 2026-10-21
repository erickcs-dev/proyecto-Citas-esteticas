@extends('layouts.app')

@section('title', 'Política de Privacidad y Tratamiento de Datos')

@section('content')
<div class="legal-container" style="max-width: 860px; margin: 2rem auto; padding: 2rem; background: var(--surface, #ffffff); border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); line-height: 1.7;">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-primary, #111827);">Política de Privacidad y Tratamiento de Datos Personales</h1>
    <p style="color: var(--text-muted, #6b7280); font-size: 0.9rem; margin-bottom: 2rem;">Última actualización: {{ date('d/m/Y') }} · En cumplimiento de la Ley 1581 de 2012 (Habeas Data) y estándares GDPR</p>

    <section style="margin-bottom: 1.75rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 0.5rem;">1. Responsable del Tratamiento</h2>
        <p><strong>Citas Estéticas S.A.S.</strong> (en adelante, "La Plataforma"), identificada con NIT 901.234.567-8, con domicilio en Colombia y correo electrónico de contacto para protección de datos: <a href="mailto:privacidad@citasesteticas.com" style="color: #6366f1;">privacidad@citasesteticas.com</a>.</p>
    </section>

    <section style="margin-bottom: 1.75rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 0.5rem;">2. Datos Personales Recolectados</h2>
        <p>Para la prestación del servicio de agendamiento y gestión de citas recopilamos:</p>
        <ul style="list-style: disc; padding-left: 1.5rem; margin-top: 0.5rem;">
            <li>Datos de identificación y contacto: Nombre completo, correo electrónico y número de teléfono celular.</li>
            <li>Datos de autenticación mediante terceros: Identificador único de Google OAuth, nombre y avatar si se opta por dicho método.</li>
            <li>Datos de las reservas: Servicios seleccionados, fechas, horarios y notas personalizadas para la atención.</li>
            <li>Información de facturación y comprobantes: Referencias de transacción y estado del anticipo. <em>Aclaración: No almacenamos números completos de tarjetas ni códigos CVV en nuestros servidores.</em></li>
        </ul>
    </section>

    <section style="margin-bottom: 1.75rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 0.5rem;">3. Finalidad del Tratamiento</h2>
        <p>Los datos personales recolectados se utilizarán exclusivamente para:</p>
        <ul style="list-style: disc; padding-left: 1.5rem; margin-top: 0.5rem;">
            <li>Gestionar, confirmar, reprogramar y notificar el estado de las citas estéticas.</li>
            <li>Conectar de forma segura a clientes con los establecimientos asociados.</li>
            <li>Cumplir con obligaciones legales, tributarias y contables aplicables.</li>
            <li>Prevenir fraudes, suplantaciones de identidad y garantizar la seguridad del sistema.</li>
        </ul>
    </section>

    <section style="margin-bottom: 1.75rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 0.5rem;">4. Derechos de los Titulares (Derechos ARCO)</h2>
        <p>De conformidad con la legislación aplicable, usted tiene derecho a:</p>
        <ul style="list-style: disc; padding-left: 1.5rem; margin-top: 0.5rem;">
            <li><strong>Acceso:</strong> Conocer los datos personales que reposan en nuestras bases de datos.</li>
            <li><strong>Rectificación:</strong> Actualizar o rectificar datos parciales, inexactos o incompletos.</li>
            <li><strong>Cancelación / Supresión:</strong> Solicitar la eliminación de su cuenta y sus datos personales ("Derecho al olvido") cuando no medie un deber legal o contractual de permanencia.</li>
            <li><strong>Revocatoria:</strong> Revocar la autorización otorgada para el tratamiento de sus datos.</li>
        </ul>
        <p style="margin-top: 0.75rem;">Para ejercer cualquiera de estos derechos, puede escribir a <a href="mailto:privacidad@citasesteticas.com" style="color: #6366f1;">privacidad@citasesteticas.com</a> o radicar una solicitud en nuestro canal de <a href="{{ route('legal.pqr') }}" style="color: #6366f1;">PQR</a>.</p>
    </section>

    <section style="margin-bottom: 1.75rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 0.5rem;">5. Seguridad y Retención de la Información</h2>
        <p>Implementamos medidas técnicas, humanas y administrativas necesarias para otorgar seguridad a los registros, evitando su adulteración, pérdida, consulta, uso o acceso no autorizado mediante cifrado en tránsito (HTTPS/TLS) y contraseñas con hash criptográfico seguro (Bcrypt/Argon2).</p>
    </section>
</div>
@endsection
