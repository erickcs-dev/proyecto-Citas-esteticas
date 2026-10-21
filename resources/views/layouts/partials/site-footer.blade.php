<footer class="site-footer" style="background: var(--surface, #ffffff); border-top: 1px solid var(--border-color, #e5e7eb); padding: 3rem 1.5rem 2rem; margin-top: 4rem; color: var(--text-muted, #6b7280); font-size: 0.9rem;">
    <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 2.5rem; margin-bottom: 2.5rem;">
        <div>
            <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary, #111827); margin-bottom: 0.75rem;">Citas Estéticas</h3>
            <p style="line-height: 1.6; margin-bottom: 0.75rem;">Plataforma inteligente para la reserva, administración y gestión segura de citas en centros de estética, spas y barberías.</p>
            <p style="font-size: 0.8rem; color: #9ca3af;">Operado por Citas Estéticas S.A.S. · NIT: 901.234.567-8</p>
        </div>

        <div>
            <h4 style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary, #111827); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Marco Legal</h4>
            <ul style="list-style: none; padding: 0; margin: 0; line-height: 2;">
                <li><a href="{{ route('legal.privacy') }}" style="color: inherit; text-decoration: none; transition: color 0.2s;">Política de Privacidad</a></li>
                <li><a href="{{ route('legal.terms') }}" style="color: inherit; text-decoration: none; transition: color 0.2s;">Términos y Condiciones</a></li>
                <li><a href="{{ route('legal.cookies') }}" style="color: inherit; text-decoration: none; transition: color 0.2s;">Política de Cookies</a></li>
                <li><a href="{{ route('legal.pqr') }}" style="color: inherit; text-decoration: none; transition: color 0.2s;">Canal de PQR (Ley 1480)</a></li>
            </ul>
        </div>

        <div>
            <h4 style="font-size: 0.95rem; font-weight: 600; color: var(--text-primary, #111827); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Seguridad y Pagos</h4>
            <p style="line-height: 1.6; margin-bottom: 0.5rem;">Procesamiento seguro de anticipos bajo estándares de la industria. Protección de datos bajo la Ley 1581 de 2012.</p>
            <div style="display: flex; gap: 0.5rem; align-items: center; margin-top: 0.75rem;">
                <span style="display: inline-block; padding: 0.25rem 0.6rem; background: #ecfdf5; color: #047857; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">SSL 256-bit</span>
                <span style="display: inline-block; padding: 0.25rem 0.6rem; background: #eef2ff; color: #4338ca; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">Habeas Data</span>
            </div>
        </div>
    </div>

    <div style="max-width: 1200px; margin: 0 auto; padding-top: 1.5rem; border-top: 1px solid var(--border-color, #f3f4f6); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; font-size: 0.85rem;">
        <div>&copy; {{ date('Y') }} Citas Estéticas. Todos los derechos reservados.</div>
        <div style="display: flex; gap: 1.5rem;">
            <span>Colombia</span>
            <a href="mailto:soporte@citasesteticas.com" style="color: inherit; text-decoration: none;">soporte@citasesteticas.com</a>
        </div>
    </div>
</footer>
