@extends('layouts.app')

@section('title', 'Términos y Condiciones de Uso')

@section('content')
<div class="legal-container" style="max-width: 860px; margin: 2rem auto; padding: 2rem; background: var(--surface, #ffffff); border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); line-height: 1.7;">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-primary, #111827);">Términos y Condiciones del Servicio</h1>
    <p style="color: var(--text-muted, #6b7280); font-size: 0.9rem; margin-bottom: 2rem;">Última actualización: {{ date('d/m/Y') }} · Regulación de Comercio Electrónico y Ley 1480 de 2011</p>

    <section style="margin-bottom: 1.75rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 0.5rem;">1. Objeto del Servicio</h2>
        <p>Los presentes Términos y Condiciones regulan el acceso y uso de la plataforma digital <strong>Citas Estéticas</strong>, la cual actúa como intermediaria tecnológica entre usuarios consumidores (clientes) y proveedores independientes de servicios de belleza, peluquería, estética y bienestar (negocios afiliados).</p>
    </section>

    <section style="margin-bottom: 1.75rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 0.5rem;">2. Registro y Cuentas de Usuario</h2>
        <p>Para agendar citas o gestionar un negocio, el usuario debe crear una cuenta aportando información veraz, vigente y verificable. El usuario es el único responsable de custodiar sus credenciales de acceso y de cualquier actividad efectuada desde su cuenta.</p>
    </section>

    <section style="margin-bottom: 1.75rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 0.5rem;">3. Política de Anticipos y Pagos</h2>
        <ul style="list-style: disc; padding-left: 1.5rem; margin-top: 0.5rem;">
            <li><strong>Reserva garantizada:</strong> Para asegurar la disponibilidad de la cita y el horario del profesional, se requiere el pago de un anticipo equivalente al <strong>50% del valor total del servicio</strong>.</li>
            <li><strong>Saldo pendiente:</strong> El 50% restante deberá ser cancelado directamente en las instalaciones del negocio o mediante los métodos de pago habilitados al momento de recibir la atención.</li>
            <li><strong>Seguridad en transacciones:</strong> Los pagos electrónicos se procesan bajo protocolos de seguridad bancaria. La plataforma no almacena números de tarjeta ni datos sensibles de pago en sus servidores.</li>
        </ul>
    </section>

    <section style="margin-bottom: 1.75rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 0.5rem;">4. Políticas de Cancelación, Reprogramación e Inasistencia (No-Show)</h2>
        <ul style="list-style: disc; padding-left: 1.5rem; margin-top: 0.5rem;">
            <li><strong>Cancelación con antelación:</strong> Las cancelaciones realizadas con al menos <strong>24 horas de anticipación</strong> a la hora fijada permitirán la reprogramación del servicio sin costo o la emisión de saldo a favor según la política particular del negocio.</li>
            <li><strong>Cancelaciones tardías e inasistencia (No-Show):</strong> Si el cliente no asiste a la cita fijada o cancela con menos de 24 horas de antelación, el anticipo del 50% no será reembolsable, destinándose a cubrir el bloqueo de agenda y el lucro cesante del profesional.</li>
            <li><strong>Excepción al Derecho de Retracto:</strong> De conformidad con el numeral 4 del artículo 47 de la Ley 1480 de 2011 (Estatuto del Consumidor), se exceptúan del derecho de retracto los contratos de prestación de servicios cuya prestación haya comenzado con el consentimiento del consumidor o servicios determinados con una fecha o periodo de ejecución específico.</li>
        </ul>
    </section>

    <section style="margin-bottom: 1.75rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 0.5rem;">5. Canales de Atención y Reclamaciones (PQR)</h2>
        <p>Cualquier reclamación, duda o solicitud será atendida a través de nuestro canal formal de <a href="{{ route('legal.pqr') }}" style="color: #6366f1;">PQR</a> o al correo <a href="mailto:soporte@citasesteticas.com" style="color: #6366f1;">soporte@citasesteticas.com</a> en un término legal máximo de quince (15) días hábiles.</p>
    </section>
</div>
@endsection
