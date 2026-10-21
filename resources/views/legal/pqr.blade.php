@extends('layouts.app')

@section('title', 'Peticiones, Quejas y Reclamos (PQR)')

@section('content')
<div class="legal-container" style="max-width: 860px; margin: 2rem auto; padding: 2rem; background: var(--surface, #ffffff); border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); line-height: 1.7;">
    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-primary, #111827);">Canal Oficial de PQR (Peticiones, Quejas y Reclamos)</h1>
    <p style="color: var(--text-muted, #6b7280); font-size: 0.9rem; margin-bottom: 2rem;">En cumplimiento del Estatuto del Consumidor (Ley 1480 de 2011) y normas de protección al usuario</p>

    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 1.25rem; margin-bottom: 2rem;">
        <h3 style="color: #166534; font-weight: 600; margin-bottom: 0.25rem;">Garantía de Respuesta Oportuna</h3>
        <p style="color: #15803d; font-size: 0.95rem; margin-bottom: 0;">De acuerdo con la legislación vigente, todas las solicitudes radicadas a través de este canal recibirán respuesta oficial en un plazo máximo de <strong>quince (15) días hábiles</strong> a partir de su radicación.</p>
    </div>

    <section style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 0.5rem;">Canales de Atención Directa</h2>
        <ul style="list-style: none; padding-left: 0; margin-top: 0.75rem;">
            <li style="margin-bottom: 0.5rem;"><strong>Correo oficial de atención:</strong> <a href="mailto:soporte@citasesteticas.com" style="color: #6366f1;">soporte@citasesteticas.com</a></li>
            <li style="margin-bottom: 0.5rem;"><strong>Línea de soporte WhatsApp:</strong> +57 (300) 123-4567 (Lunes a Viernes 8:00 AM - 6:00 PM)</li>
            <li style="margin-bottom: 0.5rem;"><strong>Domicilio principal:</strong> Cúcuta, Norte de Santander, Colombia</li>
        </ul>
    </section>

    <section style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem;">
        <h2 style="font-size: 1.2rem; font-weight: 600; color: var(--text-primary, #1f2937); margin-bottom: 1rem;">Formulario de Radicación de Solicitud</h2>
        <form onsubmit="event.preventDefault(); alert('Gracias por radicar su PQR. Su número de radicado provisional es: PQR-' + Math.floor(100000 + Math.random() * 900000) + '. Nos comunicaremos con usted en un plazo no mayor a 15 días hábiles.'); this.reset();">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: 500; margin-bottom: 0.25rem;">Nombre Completo</label>
                    <input type="text" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: 500; margin-bottom: 0.25rem;">Correo Electrónico</label>
                    <input type="email" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px;">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.9rem; font-weight: 500; margin-bottom: 0.25rem;">Tipo de Solicitud</label>
                <select required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px;">
                    <option value="">Seleccione una opción</option>
                    <option value="peticion">Petición / Consulta de información</option>
                    <option value="queja">Queja (inconformidad con la atención o servicio)</option>
                    <option value="reclamo">Reclamo (inconformidad con un cobro o anticipo)</option>
                    <option value="sugerencia">Sugerencia de mejora</option>
                    <option value="habeas_data">Solicitud de Derechos ARCO (Tratamiento de datos)</option>
                </select>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.9rem; font-weight: 500; margin-bottom: 0.25rem;">Descripción de los Hechos</label>
                <textarea rows="4" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px; resize: vertical;" placeholder="Detalla claramente los hechos, fechas, servicio o reserva relacionada..."></textarea>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.85rem; color: #4b5563;">
                    <input type="checkbox" required style="margin-top: 0.2rem;">
                    <span>Autorizo el tratamiento de mis datos personales para la atención y trámite de esta solicitud conforme a la <a href="{{ route('legal.privacy') }}" target="_blank" style="color: #6366f1;">Política de Privacidad</a>.</span>
                </label>
            </div>

            <button type="submit" style="background: #4f46e5; color: white; padding: 0.6rem 1.5rem; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Radicar Solicitud</button>
        </form>
    </section>
</div>
@endsection
