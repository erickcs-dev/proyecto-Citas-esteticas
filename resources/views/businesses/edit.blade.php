@extends('layouts.app')

@section('title', 'Personalizador de Marca y Tema - '.$business->name)
@section('theme_style', '--primary-color: '.old('primary_color', $business->brandColor()).';')

@section('content')
<div
    class="mx-auto max-w-7xl px-4 py-8"
    x-data="{
        activeColor: '{{ old('primary_color', $business->brandColor()) }}',
        activeName: '{{ old('name', $business->name) }}',
        activeSlug: '{{ old('slug', $business->slug) }}',
        activeType: '{{ old('type', $business->type) }}',
        presets: [
            { name: 'Aura Bordeaux', hex: '#7B1E34', desc: 'Vino de lujo & alta estética' },
            { name: 'Imperial Emerald', hex: '#1C3F35', desc: 'Bienestar & botánica holística' },
            { name: 'Nocturne Sapphire', hex: '#1A2B4C', desc: 'Clínica láser & dermatología' },
            { name: 'Terracotta Luxe', hex: '#994B35', desc: 'Spa & terapias termales' },
            { name: 'Antique Gold', hex: '#D4AF37', desc: 'Prestigio & barbería boutique' },
            { name: 'Deep Forest', hex: '#2C4035', desc: 'Cuidado orgánico & calma' }
        ],
        setColor(hex) {
            this.activeColor = hex;
        }
    }"
>
    <!-- Top Header & Breadcrumb -->
    <div class="mb-8 flex flex-col justify-between gap-4 border-b border-slate-200 pb-6 md:flex-row md:items-center">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-slate-700">
                    <span class="h-2 w-2 rounded-full" :style="{ backgroundColor: activeColor }"></span>
                    Configuración Multi-Tenant · Stitch UI
                </span>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">ID: #{{ $business->id }}</span>
            </div>
            <h1 class="font-display mt-2 text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">
                Personalizador de Marca y Tema
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Ajusta la paleta cromática, tipografía y datos de identidad con previsualización en vivo en tiempo real.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a
                href="{{ route('businesses.hours.index', $business) }}"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                <x-heroicon-o-clock class="h-4 w-4 text-slate-500" />
                Horarios comerciales
            </a>
            <a
                href="{{ route('businesses.services.index', $business) }}"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                <x-heroicon-o-sparkles class="h-4 w-4 text-slate-500" />
                Catálogo de servicios
            </a>
            <a
                href="{{ route('public.businesses.show', $business->slug) }}"
                target="_blank"
                class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:opacity-95"
                :style="{ backgroundColor: activeColor }"
            >
                <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" />
                Ver página pública
            </a>
        </div>
    </div>

    <!-- Main Grid: 2 Columns (Editor vs Live Preview) -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
        <!-- Left Column: Form & Style Controls (7 cols) -->
        <div class="space-y-6 lg:col-span-7">
            <form method="POST" action="{{ route('businesses.update', $business) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Color Palette Card -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Paleta Cromática de Marca</h2>
                            <p class="text-xs text-slate-500">Selecciona un preset de alta gama o ingresa tu código HEX institucional.</p>
                        </div>
                        <span class="rounded-lg border border-slate-200 px-2.5 py-1 text-xs font-mono font-semibold" :style="{ color: activeColor }" x-text="activeColor"></span>
                    </div>

                    <!-- Presets Grid -->
                    <div class="mt-4">
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Colecciones recomendadas</label>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                            <template x-for="p in presets" :key="p.hex">
                                <button
                                    type="button"
                                    @click="setColor(p.hex)"
                                    class="flex items-center gap-3 rounded-xl border p-2.5 text-left transition"
                                    :class="activeColor.toUpperCase() === p.hex.toUpperCase() ? 'border-slate-900 ring-2 ring-slate-900/10 bg-slate-50' : 'border-slate-200 bg-white hover:bg-slate-50'"
                                >
                                    <span class="h-7 w-7 shrink-0 rounded-lg shadow-inner" :style="{ backgroundColor: p.hex }"></span>
                                    <div class="min-w-0">
                                        <p class="truncate text-xs font-semibold text-slate-900" x-text="p.name"></p>
                                        <p class="text-[10px] font-mono text-slate-500" x-text="p.hex"></p>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Custom Color Input -->
                    <div class="mt-5 rounded-xl border border-slate-100 bg-slate-50/80 p-4">
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-600">Color Primario Personalizado</label>
                        <div class="flex items-center gap-3">
                            <input
                                type="color"
                                x-model="activeColor"
                                class="h-11 w-14 cursor-pointer rounded-lg border border-slate-200 bg-white p-1"
                                aria-label="Selector interactivo de color"
                            >
                            <div class="relative flex-1">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-mono text-slate-400">HEX</span>
                                <input
                                    id="primary_color"
                                    name="primary_color"
                                    type="text"
                                    x-model="activeColor"
                                    pattern="^#[0-9A-Fa-f]{6}$"
                                    maxlength="7"
                                    required
                                    class="block w-full rounded-xl border border-slate-200 bg-white py-2.5 pr-3 pl-12 text-sm font-mono text-slate-900 shadow-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Typography Spec Indicator -->
                    <div class="mt-4 flex items-center justify-between rounded-xl border border-slate-100 bg-white p-3 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="font-display text-lg font-bold text-slate-900">Aa</span>
                            <div>
                                <span class="font-medium text-slate-700">Tipografía: EB Garamond (Display) + Inter (Cuerpo)</span>
                                <p class="text-[11px] text-slate-400">Alineado al sistema "Aesthetic Precision" de Google Stitch</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-semibold text-emerald-700">Activo</span>
                    </div>
                </div>

                <!-- Business Identity & URL Card -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="border-b border-slate-100 pb-4">
                        <h2 class="text-base font-semibold text-slate-900">Identidad y Enlace Público</h2>
                        <p class="text-xs text-slate-500">Define el nombre comercial y el enlace directo para tus clientes.</p>
                    </div>

                    <div class="mt-5 space-y-4">
                        <div>
                            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Nombre del Negocio</label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                x-model="activeName"
                                value="{{ old('name', $business->name) }}"
                                required
                                class="mt-1.5 block w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                            >
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Slug / Enlace Público</label>
                                <div class="mt-1.5 flex rounded-xl border border-slate-200 shadow-sm focus-within:border-slate-900 focus-within:ring-1 focus-within:ring-slate-900">
                                    <span class="inline-flex items-center rounded-l-xl border-r border-slate-200 bg-slate-50 px-3 text-xs text-slate-500">/negocios/</span>
                                    <input
                                        id="slug"
                                        name="slug"
                                        type="text"
                                        x-model="activeSlug"
                                        value="{{ old('slug', $business->slug) }}"
                                        required
                                        class="block w-full rounded-r-xl border-0 py-2.5 px-3 text-sm text-slate-900 focus:ring-0"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="type" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Tipo de Negocio</label>
                                <select
                                    id="type"
                                    name="type"
                                    x-model="activeType"
                                    required
                                    class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                                >
                                    <option value="estetica" @selected(old('type', $business->type) === 'estetica')>Estética & Belleza</option>
                                    <option value="barberia" @selected(old('type', $business->type) === 'barberia')>Barbería & Peluquería</option>
                                    <option value="odontologia" @selected(old('type', $business->type) === 'odontologia')>Odontología & Salud</option>
                                    <option value="consultorio" @selected(old('type', $business->type) === 'consultorio')>Consultorio Especializado</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Correo de Contacto</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email', $business->email) }}"
                                    class="mt-1.5 block w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                                >
                            </div>

                            <div>
                                <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Teléfono / WhatsApp</label>
                                <input
                                    id="phone"
                                    name="phone"
                                    type="text"
                                    value="{{ old('phone', $business->phone) }}"
                                    class="mt-1.5 block w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Dirección Física de Atención</label>
                            <input
                                id="address"
                                name="address"
                                type="text"
                                value="{{ old('address', $business->address) }}"
                                class="mt-1.5 block w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                            >
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                        <strong class="font-semibold">Revisa los siguientes campos:</strong>
                        <ul class="mt-1 list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Submit Action Bar -->
                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs text-slate-500">Los cambios se aplicarán de inmediato a tu página pública.</p>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold text-white shadow-md transition-all hover:opacity-95"
                        :style="{ backgroundColor: activeColor }"
                    >
                        <x-heroicon-o-check class="h-4 w-4" />
                        Guardar Configuración
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Column: Live Interactive Mockup (5 cols) -->
        <div class="lg:col-span-5">
            <div class="sticky top-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <span class="h-2 w-2 animate-ping rounded-full bg-emerald-500"></span>
                        Previsualización en Vivo
                    </span>
                    <span class="text-[11px] text-slate-400">Desktop Viewport</span>
                </div>

                <!-- Mockup Browser Shell -->
                <div class="overflow-hidden rounded-2xl border border-slate-300 bg-white shadow-xl transition-all duration-300">
                    <!-- Browser Chrome Bar -->
                    <div class="flex items-center gap-2 border-b border-slate-200 bg-slate-100/90 px-4 py-2.5">
                        <div class="flex gap-1.5">
                            <span class="h-2.5 w-2.5 rounded-full bg-rose-400"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                        </div>
                        <div class="mx-auto flex w-3/4 items-center justify-center rounded-md bg-white px-2 py-0.5 text-[11px] text-slate-500 shadow-inner">
                            <x-heroicon-o-lock-closed class="mr-1 h-3 w-3 text-emerald-600" />
                            citas.app/negocios/<span class="font-medium text-slate-800" x-text="activeSlug || 'aura-spa'"></span>
                        </div>
                    </div>

                    <!-- Inner Mockup Body (Stitch Design Mirror) -->
                    <div class="bg-[#FAFBFD] p-5">
                        <!-- Hero Banner in Mockup -->
                        <div
                            class="relative overflow-hidden rounded-xl p-5 text-white shadow-md transition-all"
                            :style="{ backgroundColor: activeColor }"
                        >
                            <div class="relative z-10">
                                <span class="inline-block rounded-full bg-white/20 px-2.5 py-0.5 text-[10px] font-semibold tracking-wider uppercase backdrop-blur-sm" x-text="activeType || 'Estética'"></span>
                                <h3 class="font-display mt-2 text-2xl font-bold tracking-tight text-white" x-text="activeName || 'Tu Negocio'"></h3>
                                <p class="mt-1 text-xs text-white/80">Experiencia de bienestar, estética de precisión y reserva online.</p>
                                
                                <div class="mt-3 flex items-center gap-2 text-[11px] text-white/90">
                                    <span class="inline-flex items-center text-amber-300">★★★★★</span>
                                    <span>5.0 (48 reseñas)</span>
                                </div>
                            </div>
                            <div class="absolute -right-8 -bottom-8 h-32 w-32 rounded-full bg-white/10 blur-xl"></div>
                        </div>

                        <!-- Sample Treatment Card in Mockup -->
                        <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">60 MIN</span>
                                    <h4 class="font-display mt-1 text-base font-semibold text-slate-900">Limpieza Facial Profunda Glow</h4>
                                    <p class="text-[11px] text-slate-500">Exfoliación ultrasónica, hidratación con sérum y masaje linfático.</p>
                                </div>
                                <span class="font-semibold text-slate-900 text-sm">$120.000</span>
                            </div>

                            <div class="mt-3 border-t border-slate-100 pt-3">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-slate-400">Horarios Disponibles Hoy</p>
                                <div class="mt-1.5 flex flex-wrap gap-1.5">
                                    <span class="rounded-lg border px-2 py-1 text-[11px] font-semibold text-white shadow-xs" :style="{ backgroundColor: activeColor, borderColor: activeColor }">10:00 AM</span>
                                    <span class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] text-slate-600">11:30 AM</span>
                                    <span class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] text-slate-600">03:00 PM</span>
                                    <span class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] text-slate-600">04:30 PM</span>
                                </div>
                            </div>

                            <button
                                type="button"
                                class="mt-4 flex w-full items-center justify-center gap-1.5 rounded-lg py-2 text-xs font-semibold text-white shadow-xs transition hover:opacity-90"
                                :style="{ backgroundColor: activeColor }"
                            >
                                <x-heroicon-o-calendar-days class="h-3.5 w-3.5" />
                                Reservar Cita (Anticipo 50%)
                            </button>
                        </div>

                        <!-- Security & Guarantee Badge in Mockup -->
                        <div class="mt-4 flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50 p-2.5 text-[10px] text-slate-500">
                            <span class="flex items-center gap-1">
                                <x-heroicon-s-shield-check class="h-3.5 w-3.5 text-emerald-600" />
                                Pago seguro verificado
                            </span>
                            <span class="text-slate-400">Cancelación flexible 24h</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
