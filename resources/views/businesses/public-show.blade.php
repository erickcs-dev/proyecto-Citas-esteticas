@extends('layouts.app')

@section('title', $business->name.' - Reserva Online')
@section('theme_style', '--primary-color: '.$business->brandColor().';')
@section('hide_header', 'true')
@section('hide_flash', 'true')
@section('main_class', '')
@section('container_class', '')

@section('content')
<div
    class="min-h-screen bg-[#FAFBFD] text-slate-900"
    x-data="{
        selectedServiceId: {{ $business->services->first()?->id ?? 'null' }},
        selectedServiceName: {{ e(json_encode($business->services->first()?->name ?? 'Selecciona un tratamiento')) }},
        selectedServicePrice: {{ (float) ($business->services->first()?->price ?? 0) }},
        selectedServiceDuration: {{ (int) ($business->services->first()?->duration_minutes ?? 60) }},
        selectedSpecialist: 'Dra. Sofía Martínez',
        selectedDate: '{{ now()->addDay()->toDateString() }}',
        selectedTime: '10:00',
        activeCategory: 'all',
        openLightbox: false,
        lightboxSrc: '',
        selectService(id, name, price, duration) {
            this.selectedServiceId = id;
            this.selectedServiceName = name;
            this.selectedServicePrice = price;
            this.selectedServiceDuration = duration;
        },
        get advanceAmount() {
            return (this.selectedServicePrice * 0.50).toFixed(2);
        },
        get remainingBalance() {
            return (this.selectedServicePrice * 0.50).toFixed(2);
        }
    }"
>
    <!-- Top Floating Navigation Bar -->
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('public.businesses.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition">
                    <x-heroicon-o-arrow-left class="h-4 w-4" />
                    Catálogo
                </a>
                <span class="text-slate-300">/</span>
                <span class="font-display text-lg font-semibold tracking-tight text-slate-900">{{ $business->name }}</span>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50">
                        Ir al Panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}" class="rounded-xl px-4 py-2 text-xs font-semibold text-white shadow-xs transition hover:opacity-95" style="background-color: var(--primary-color);">
                        Registrarse
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Luxury Editorial Hero (Stitch Aura Spa Theme) -->
    <section class="relative overflow-hidden bg-slate-950 py-16 text-white md:py-24">
        <!-- Background Wallpaper with Overlay -->
        <div
            class="absolute inset-0 bg-cover bg-center opacity-30 mix-blend-luminosity filter blur-[0.5px]"
            style="background-image: url('https://images.unsplash.com/photo-1540555700478-4be289fbbe4f?auto=format&fit=crop&w=2000&q=80');"
        ></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/70 to-transparent"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-3xl">
                <!-- Eyebrow Badge -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-amber-200 backdrop-blur-sm">
                        <x-heroicon-s-sparkles class="h-3.5 w-3.5 text-amber-300" />
                        {{ $business->type }} · ATELIER DE BIENESTAR
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-300 backdrop-blur-sm">
                        <x-heroicon-s-shield-check class="h-3.5 w-3.5" />
                        Negocio Verificado
                    </span>
                </div>

                <!-- Display Title (EB Garamond) -->
                <h1 class="font-display mt-4 text-4xl font-bold tracking-tight text-white sm:text-5xl md:text-6xl">
                    {{ $business->name }}
                </h1>

                <p class="mt-4 text-base leading-relaxed text-slate-300 sm:text-lg">
                    Experiencia de armonía integral, dermatología estética y rituales relajantes. Reserva tu turno de forma transparente con depósito del 50% y garantía de puntualidad.
                </p>

                <!-- Reviews & Location Badges -->
                <div class="mt-6 flex flex-wrap items-center gap-4 text-sm text-slate-300">
                    <div class="flex items-center gap-1.5 rounded-full border border-white/15 bg-white/5 px-3.5 py-1.5 backdrop-blur-xs">
                        <div class="flex text-amber-400">
                            ★★★★★
                        </div>
                        <span class="font-semibold text-white">{{ number_format((float) ($business->reviews_avg_rating ?? 5.0), 1) }}</span>
                        <span class="text-xs text-slate-400">({{ $business->reviews_count }} reseñas)</span>
                    </div>

                    @if ($business->address)
                        <div class="flex items-center gap-1.5 text-xs text-slate-300">
                            <x-heroicon-o-map-pin class="h-4 w-4 text-amber-300" />
                            <span>{{ $business->address }}</span>
                        </div>
                    @endif

                    @if ($business->phone)
                        <div class="flex items-center gap-1.5 text-xs text-slate-300">
                            <x-heroicon-o-phone class="h-4 w-4 text-amber-300" />
                            <span>{{ $business->phone }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Container: 3-Step Booking Engine -->
    <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
            <!-- Left Column: Steps 1 & 2 (8 cols) -->
            <div class="space-y-12 lg:col-span-8">
                <!-- STEP 1: Treatment Catalog -->
                <section id="services-section">
                    <div class="mb-6 flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-widest text-[var(--primary-color)]">Paso 1 de 3</span>
                            <h2 class="font-display text-2xl font-semibold text-slate-900 sm:text-3xl">
                                Catálogo de Tratamientos y Procedimientos
                            </h2>
                            <p class="text-xs text-slate-500">Selecciona el servicio que deseas agendar para ver los horarios disponibles.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @forelse ($business->services as $service)
                            <div
                                @click="selectService({{ $service->id }}, {{ e(json_encode($service->name)) }}, {{ (float) $service->price }}, {{ (int) $service->duration_minutes }})"
                                class="cursor-pointer rounded-2xl border bg-white p-5 transition-all duration-200"
                                :class="selectedServiceId === {{ $service->id }}
                                    ? 'border-slate-900 ring-2 ring-slate-900/10 shadow-md'
                                    : 'border-slate-200 hover:border-slate-300 hover:shadow-sm'"
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-semibold text-slate-700">
                                            <x-heroicon-o-clock class="h-3 w-3" />
                                            {{ $service->duration_minutes }} MIN
                                        </span>
                                        <h3 class="font-display mt-2 text-lg font-semibold text-slate-900 leading-snug">
                                            {{ $service->name }}
                                        </h3>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-mono text-base font-bold text-slate-900">
                                            ${{ number_format((float) $service->price, 2) }}
                                        </span>
                                        <p class="text-[10px] text-slate-500">Anticipo: 50%</p>
                                    </div>
                                </div>

                                <p class="mt-2.5 text-xs leading-relaxed text-slate-600 line-clamp-2">
                                    {{ $service->description ?: 'Tratamiento profesional realizado con técnicas de vanguardia y productos hipoalergénicos.' }}
                                </p>

                                @if ($service->galleryImageUrls() !== [])
                                    <div class="mt-3 flex gap-2 overflow-x-auto pb-1">
                                        @foreach (array_slice($service->galleryImageUrls(), 0, 3) as $imgUrl)
                                            <img
                                                src="{{ $imgUrl }}"
                                                alt="{{ $service->name }}"
                                                class="h-14 w-14 shrink-0 rounded-lg border border-slate-200 object-cover"
                                                loading="lazy"
                                            >
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                                    <span class="text-[11px] font-medium" :class="selectedServiceId === {{ $service->id }} ? 'text-slate-900 font-bold' : 'text-slate-400'">
                                        <span x-show="selectedServiceId === {{ $service->id }}">✓ Seleccionado</span>
                                        <span x-show="selectedServiceId !== {{ $service->id }}">Clic para seleccionar</span>
                                    </span>
                                    <span
                                        class="rounded-lg px-3 py-1 text-xs font-semibold transition"
                                        :class="selectedServiceId === {{ $service->id }}
                                            ? 'bg-slate-900 text-white'
                                            : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                    >
                                        Elegir
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-8 text-center sm:col-span-2">
                                <p class="text-sm text-slate-500">Actualmente este negocio no tiene servicios activos publicados.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                <!-- STEP 2: Specialist & Schedule Selector (Stitch Slots Matrix) -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <span class="text-xs font-bold uppercase tracking-widest text-[var(--primary-color)]">Paso 2 de 3</span>
                    <h2 class="font-display mt-1 text-2xl font-semibold text-slate-900">
                        Especialista, Fecha y Turno Horario
                    </h2>
                    <p class="text-xs text-slate-500">Configura los parámetros de tu cita para el tratamiento seleccionado.</p>

                    <!-- Specialists Row -->
                    <div class="mt-6">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Especialista Asignado</label>
                        <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <button
                                type="button"
                                @click="selectedSpecialist = 'Dra. Sofía Martínez'"
                                class="flex items-center gap-3 rounded-xl border p-3 text-left transition"
                                :class="selectedSpecialist === 'Dra. Sofía Martínez' ? 'border-slate-900 bg-slate-50 ring-1 ring-slate-900' : 'border-slate-200 bg-white hover:bg-slate-50'"
                            >
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-800 font-bold text-sm">
                                    SM
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-900">Dra. Sofía Martínez</p>
                                    <p class="text-[11px] text-slate-500">Cosmiatra & Especialista Láser</p>
                                </div>
                            </button>

                            <button
                                type="button"
                                @click="selectedSpecialist = 'Lic. Camila Restrepo'"
                                class="flex items-center gap-3 rounded-xl border p-3 text-left transition"
                                :class="selectedSpecialist === 'Lic. Camila Restrepo' ? 'border-slate-900 bg-slate-50 ring-1 ring-slate-900' : 'border-slate-200 bg-white hover:bg-slate-50'"
                            >
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-800 font-bold text-sm">
                                    CR
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-900">Lic. Camila Restrepo</p>
                                    <p class="text-[11px] text-slate-500">Fisioterapeuta & Terapias Holísticas</p>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Date & Time Slots -->
                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Fecha Deseada</label>
                        <input
                            type="date"
                            x-model="selectedDate"
                            min="{{ now()->toDateString() }}"
                            class="mt-1.5 block w-full max-w-xs rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                        >

                        <div class="mt-6">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Turnos Horarios Disponibles</label>
                            
                            <!-- Morning Slots -->
                            <div class="mt-2">
                                <span class="text-[11px] font-medium text-slate-400">Jornada de la Mañana</span>
                                <div class="mt-1.5 flex flex-wrap gap-2">
                                    <template x-for="time in ['09:00', '10:00', '11:00', '11:30']" :key="time">
                                        <button
                                            type="button"
                                            @click="selectedTime = time"
                                            class="rounded-xl border px-3.5 py-2 text-xs font-semibold transition"
                                            :class="selectedTime === time
                                                ? 'bg-slate-900 text-white border-slate-900 shadow-xs'
                                                : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                            x-text="time + ' AM'"
                                        ></button>
                                    </template>
                                </div>
                            </div>

                            <!-- Afternoon Slots -->
                            <div class="mt-4">
                                <span class="text-[11px] font-medium text-slate-400">Jornada de la Tarde</span>
                                <div class="mt-1.5 flex flex-wrap gap-2">
                                    <template x-for="time in ['02:00', '03:30', '04:30', '06:00']" :key="time">
                                        <button
                                            type="button"
                                            @click="selectedTime = time"
                                            class="rounded-xl border px-3.5 py-2 text-xs font-semibold transition"
                                            :class="selectedTime === time
                                                ? 'bg-slate-900 text-white border-slate-900 shadow-xs'
                                                : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                                            x-text="time + ' PM'"
                                        ></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Business Operating Hours Table -->
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="font-display text-xl font-semibold text-slate-900">Horarios de Atención Semanales</h3>
                    <p class="text-xs text-slate-500">Jornadas oficiales de atención al público en sede física.</p>

                    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-7">
                        @forelse ($business->hours as $hour)
                            <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3 text-center">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                    {{ $dayLabels[$hour->day_of_week] ?? 'Día '.$hour->day_of_week }}
                                </p>
                                <p class="mt-1 text-xs font-semibold text-slate-900">{{ $hour->opens_at }} - {{ $hour->closes_at }}</p>
                            </div>
                        @empty
                            <div class="col-span-full text-center text-xs text-slate-400 py-3">
                                Horarios flexibles previa cita.
                            </div>
                        @endforelse
                    </div>
                </section>

                <!-- Verified Reviews & Client Feedback -->
                <section id="reviews" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-display text-xl font-semibold text-slate-900">Reseñas y Experiencias de Clientes</h3>
                            <p class="text-xs text-slate-500">Opiniones verificadas tras tratamientos completados en nuestra plataforma.</p>
                        </div>
                        <div class="flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                            <x-heroicon-s-star class="h-4 w-4 text-amber-500" />
                            {{ number_format((float) ($business->reviews_avg_rating ?? 5.0), 1) }} / 5.0
                        </div>
                    </div>

                    @auth
                        @if ($canReview)
                            <form
                                method="POST"
                                action="{{ route('public.businesses.reviews.store', $business) }}"
                                enctype="multipart/form-data"
                                class="mt-6 rounded-xl border border-slate-100 bg-slate-50 p-4"
                            >
                                @csrf
                                <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-700">Califica tu experiencia</h4>
                                <div class="mt-3 grid gap-3 sm:grid-cols-3">
                                    <div>
                                        <label class="block text-xs text-slate-500">Puntaje</label>
                                        <select name="rating" class="mt-1 block w-full rounded-lg border-slate-200 text-xs text-slate-900">
                                            <option value="5">★★★★★ (5 - Excelente)</option>
                                            <option value="4">★★★★☆ (4 - Muy bueno)</option>
                                            <option value="3">★★★☆☆ (3 - Aceptable)</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs text-slate-500">Comentario</label>
                                        <input type="text" name="comment" required placeholder="Describe tu tratamiento..." class="mt-1 block w-full rounded-lg border-slate-200 text-xs text-slate-900">
                                    </div>
                                </div>
                                <div class="mt-3 flex justify-end">
                                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white">Publicar reseña</button>
                                </div>
                            </form>
                        @endif
                    @endauth

                    <div class="mt-6 space-y-4">
                        @forelse ($business->reviews as $review)
                            <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-xs font-bold text-slate-700">
                                            {{ substr($review->user->name ?? 'C', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-900">{{ $review->user->name ?? 'Cliente verificado' }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $review->created_at?->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="text-amber-500 text-xs">
                                        {{ str_repeat('★', (int) $review->rating) }}
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-slate-600 leading-relaxed">{{ $review->comment }}</p>
                            </div>
                        @empty
                            <p class="text-center text-xs text-slate-400 py-4">Sé el primero en calificar tu experiencia tras completar tu cita.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <!-- Right Column: Sticky Summary & Reservation Action (4 cols) -->
            <div class="lg:col-span-4">
                <div class="sticky top-20 rounded-2xl border border-slate-200 bg-white p-6 shadow-xl">
                    <span class="text-xs font-bold uppercase tracking-widest text-[var(--primary-color)]">Paso 3 de 3</span>
                    <h3 class="font-display mt-1 text-2xl font-bold text-slate-900">Resumen de tu Reserva</h3>
                    <p class="text-xs text-slate-500">Revisa los detalles antes de pasar a la confirmación segura.</p>

                    <div class="mt-5 space-y-4 rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                        <div>
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Tratamiento</span>
                            <p class="font-display text-base font-bold text-slate-900" x-text="selectedServiceName"></p>
                            <p class="text-[11px] text-slate-500" x-text="selectedServiceDuration + ' minutos de sesión'"></p>
                        </div>

                        <div class="border-t border-slate-200/80 pt-3">
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Especialista</span>
                            <p class="text-xs font-semibold text-slate-800" x-text="selectedSpecialist"></p>
                        </div>

                        <div class="border-t border-slate-200/80 pt-3">
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Fecha y Turno</span>
                            <p class="font-mono text-xs font-semibold text-slate-800">
                                <span x-text="selectedDate"></span> · <span x-text="selectedTime"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Financial Breakdown (50% Advance Model) -->
                    <div class="mt-5 space-y-2.5 border-t border-slate-100 pt-5 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Valor total del tratamiento</span>
                            <span class="font-mono font-semibold text-slate-900" x-text="'$' + Number(selectedServicePrice).toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-slate-900 font-semibold rounded-lg bg-emerald-50 px-3 py-2 text-emerald-800">
                            <span>Anticipo requerido (50%)</span>
                            <span class="font-mono font-bold" x-text="'$' + advanceAmount"></span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>Saldo a liquidar en clínica</span>
                            <span class="font-mono" x-text="'$' + remainingBalance"></span>
                        </div>
                    </div>

                    <!-- Direct Action CTA Button -->
                    <div class="mt-6">
                        @auth
                            <a
                                :href="'{{ route('appointments.create', ['business' => $business->slug]) }}?service_id=' + selectedServiceId + '&date=' + selectedDate + '&time=' + selectedTime"
                                class="flex w-full items-center justify-center gap-2 rounded-xl py-3.5 text-sm font-semibold text-white shadow-md transition-all hover:opacity-95"
                                style="background-color: var(--primary-color);"
                            >
                                <x-heroicon-o-credit-card class="h-4 w-4" />
                                Continuar y Confirmar Cita
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="flex w-full items-center justify-center gap-2 rounded-xl py-3.5 text-sm font-semibold text-white shadow-md transition-all hover:opacity-95"
                                style="background-color: var(--primary-color);"
                            >
                                <x-heroicon-o-lock-closed class="h-4 w-4" />
                                Iniciar Sesión para Reservar
                            </a>
                        @endauth
                    </div>

                    <!-- Trust and Guarantees Badge -->
                    <div class="mt-5 space-y-2 rounded-xl border border-slate-100 bg-slate-50 p-3 text-[11px] text-slate-500">
                        <div class="flex items-center gap-2">
                            <x-heroicon-s-shield-check class="h-4 w-4 text-emerald-600 shrink-0" />
                            <span>Pago cifrado y seguro conforme a PCI-DSS</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-s-clock class="h-4 w-4 text-amber-600 shrink-0" />
                            <span>Cancelación flexible hasta 24h previas</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-s-check-badge class="h-4 w-4 text-blue-600 shrink-0" />
                            <span>Tratamiento de datos según Ley 1581 (Habeas Data)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
