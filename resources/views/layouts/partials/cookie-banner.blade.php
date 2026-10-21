<div 
    x-data="{
        showBanner: false,
        init() {
            if (!localStorage.getItem('citas_cookie_consent')) {
                this.showBanner = true;
            }
        },
        acceptAll() {
            localStorage.setItem('citas_cookie_consent', 'all');
            this.showBanner = false;
        },
        acceptEssential() {
            localStorage.setItem('citas_cookie_consent', 'essential');
            this.showBanner = false;
        }
    }"
    x-show="showBanner"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-4"
    style="position: fixed; bottom: 1.5rem; left: 1.5rem; right: 1.5rem; max-width: 720px; margin: 0 auto; background: #1f2937; color: #f9fafb; padding: 1.25rem 1.5rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.25); z-index: 9999; display: flex; flex-direction: column; gap: 1rem; border: 1px solid #374151;"
>
    <div style="font-size: 0.9rem; line-height: 1.5;">
        <strong style="color: #ffffff; display: block; margin-bottom: 0.25rem; font-size: 0.95rem;">🍪 Respetamos tu privacidad</strong>
        Utilizamos cookies técnicas necesarias para el funcionamiento seguro de la plataforma y cookies opcionales para analizar el rendimiento. Puedes consultar el detalle en nuestra 
        <a href="{{ route('legal.cookies') }}" style="color: #a5b4fc; text-decoration: underline;">Política de Cookies</a>.
    </div>

    <div style="display: flex; gap: 0.75rem; justify-content: flex-end; flex-wrap: wrap;">
        <button 
            type="button" 
            @click="acceptEssential()"
            style="padding: 0.5rem 1rem; background: transparent; color: #e5e7eb; border: 1px solid #4b5563; border-radius: 6px; font-size: 0.85rem; font-weight: 500; cursor: pointer;"
        >
            Solo esenciales
        </button>
        <button 
            type="button" 
            @click="acceptAll()"
            style="padding: 0.5rem 1.25rem; background: #4f46e5; color: #ffffff; border: none; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer;"
        >
            Aceptar todas
        </button>
    </div>
</div>
