@extends('layouts.app')

@section('title', 'Dashboard de Operaciones y Calendario - citas-app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8" x-data="{ viewMode: 'calendar', statusFilter: 'all' }">
    <!-- Top Operational Bar -->
    <div class="mb-8 flex flex-col justify-between gap-4 border-b border-slate-200 pb-6 md:flex-row md:items-center">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-slate-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Centro de Operaciones · Stitch UI
                </span>
                <span class="text-xs text-slate-400">·</span>
                <span class="text-xs font-medium text-slate-500 capitalize">{{ now()->translatedFormat('l, d \d\e F \d\e Y') }}</span>
            </div>
            <h1 class="font-display mt-2 text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">
                Bienvenido, {{ $user->name }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Monitorea la agenda de citas, seña del 50% recaudada y estado operativo de tus servicios en tiempo real.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a
                href="{{ route('appointments.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-800"
            >
                <x-heroicon-o-plus class="h-4 w-4" />
                Nueva cita
            </a>

            @if ($user->canManageBusinesses())
                <a
                    href="{{ route('businesses.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                >
                    <x-heroicon-o-building-storefront class="h-4 w-4 text-slate-500" />
                    Mis Negocios
                </a>
            @endif

            <a
                href="{{ route('public.businesses.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                <x-heroicon-o-magnifying-glass class="h-4 w-4 text-slate-500" />
                Explorar catálogo público
            </a>
        </div>
    </div>

    <!-- KPI Metric Cards (Stitch Top Grid) -->
    <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card 1: Today's Appointments -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-slate-300">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Citas para Hoy</span>
                <span class="rounded-lg bg-rose-50 p-2 text-rose-600">
                    <x-heroicon-o-calendar-days class="h-5 w-5" />
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-bold tracking-tight text-slate-900">{{ $stats['today_appointments'] }}</span>
                <span class="text-xs font-medium text-slate-500">en agenda</span>
            </div>
            <div class="mt-3 flex items-center gap-2 border-t border-slate-100 pt-3 text-xs text-slate-500">
                <span class="inline-flex items-center text-emerald-600 font-medium">
                    <span class="mr-1 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    {{ $stats['confirmed_appointments'] }} confirmadas
                </span>
                <span>·</span>
                <span class="text-amber-600 font-medium">
                    {{ $stats['pending_appointments'] }} pendientes
                </span>
            </div>
        </div>

        <!-- Card 2: 50% Advance Deposits Collected -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-slate-300">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Señas Recaudadas (50%)</span>
                <span class="rounded-lg bg-emerald-50 p-2 text-emerald-600">
                    <x-heroicon-o-banknotes class="h-5 w-5" />
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-900">${{ number_format($stats['total_advance_collected'], 2) }}</span>
            </div>
            <p class="mt-3 border-t border-slate-100 pt-3 text-xs text-slate-500">
                Garantía de reserva comercial (PCI-DSS)
            </p>
        </div>

        <!-- Card 3: Total Appointments Tracked -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-slate-300">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Histórico</span>
                <span class="rounded-lg bg-blue-50 p-2 text-blue-600">
                    <x-heroicon-o-chart-bar class="h-5 w-5" />
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-bold tracking-tight text-slate-900">{{ $stats['appointments'] }}</span>
                <span class="text-xs font-medium text-slate-500">reservas</span>
            </div>
            <p class="mt-3 border-t border-slate-100 pt-3 text-xs text-slate-500">
                Historial disponible en tu cuenta
            </p>
        </div>

        <!-- Card 4: Active Services & Locations -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-slate-300">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Servicios & Sedes</span>
                <span class="rounded-lg bg-amber-50 p-2 text-amber-600">
                    <x-heroicon-o-sparkles class="h-5 w-5" />
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-3xl font-bold tracking-tight text-slate-900">{{ $stats['services'] }}</span>
                <span class="text-xs font-medium text-slate-500">servicios activos</span>
            </div>
            <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3 text-xs text-slate-500">
                <span>{{ $stats['businesses'] }} {{ $stats['businesses'] === 1 ? 'negocio administrado' : 'negocios administrados' }}</span>
            </div>
        </div>
    </div>

    <!-- Administrative Approvals Banner (If Any) -->
    @if ($user->isAdmin() && $pendingBusinessRequests->isNotEmpty())
        <div class="mb-8 rounded-2xl border border-amber-200 bg-amber-50/60 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="rounded-lg bg-amber-100 p-2 text-amber-700">
                        <x-heroicon-o-shield-check class="h-6 w-6" />
                    </span>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Solicitudes de Acceso Business Pendientes ({{ $pendingBusinessRequests->count() }})</h2>
                        <p class="text-xs text-slate-600">Los siguientes usuarios han completado el perfil y solicitaron autorización para registrar sedes y servicios.</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($pendingBusinessRequests as $applicant)
                    <div class="flex items-center justify-between rounded-xl border border-amber-200 bg-white p-3.5 shadow-xs">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $applicant->name }}</p>
                            <p class="text-xs text-slate-500">{{ $applicant->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('business-access.approve', $applicant) }}">
                            @csrf
                            <button type="submit" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-800">
                                Aprobar
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Main Operations Section: Scheduling Matrix & Queue -->
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <!-- Section Header & View Toggles -->
        <div class="flex flex-col justify-between gap-4 border-b border-slate-200 p-6 md:flex-row md:items-center">
            <div>
                <h2 class="font-display text-2xl font-semibold text-slate-900">
                    Agenda y Calendario de Operaciones
                </h2>
                <p class="text-xs text-slate-500">
                    Planificación semanal y cola de atención inmediata conforme al diseño de Stitch.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- View Mode Switcher -->
                <div class="flex rounded-xl border border-slate-200 bg-slate-50 p-1">
                    <button
                        type="button"
                        @click="viewMode = 'calendar'"
                        class="rounded-lg px-3 py-1.5 text-xs font-semibold transition"
                        :class="viewMode === 'calendar' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-700'"
                    >
                        Vista Matriz / Calendario
                    </button>
                    <button
                        type="button"
                        @click="viewMode = 'list'"
                        class="rounded-lg px-3 py-1.5 text-xs font-semibold transition"
                        :class="viewMode === 'list' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-700'"
                    >
                        Cola Operativa de Citas
                    </button>
                </div>

                <a
                    href="{{ route('appointments.index') }}"
                    class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50"
                >
                    Ver todas ({{ $stats['appointments'] }})
                </a>
            </div>
        </div>

        <!-- View 1: Weekly Scheduling Matrix (Stitch Calendar Grid) -->
        <div x-show="viewMode === 'calendar'" class="p-6">
            <!-- Day Columns Headers -->
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-5">
                @php
                    $weekDays = [
                        ['label' => 'Lunes', 'code' => 'lun'],
                        ['label' => 'Martes', 'code' => 'mar'],
                        ['label' => 'Miércoles', 'code' => 'mie'],
                        ['label' => 'Jueves', 'code' => 'jue'],
                        ['label' => 'Viernes', 'code' => 'vie'],
                    ];
                @endphp

                @foreach ($weekDays as $index => $day)
                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                        <div class="flex items-center justify-between border-b border-slate-200/80 pb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-700">{{ $day['label'] }}</span>
                            <span class="text-[10px] font-medium text-slate-400">Jornada</span>
                        </div>

                        <!-- Slots container -->
                        <div class="mt-3 space-y-2.5">
                            @php
                                $dayAppointments = $recentAppointments->filter(function ($apt) use ($index) {
                                    $carbonDate = \Carbon\Carbon::parse($apt->appointment_date);
                                    return $carbonDate->dayOfWeekIso === ($index + 1);
                                });
                            @endphp

                            @forelse ($dayAppointments as $appointment)
                                <div
                                    class="group rounded-xl border p-2.5 text-xs shadow-xs transition hover:shadow-md"
                                    style="border-left: 3px solid {{ $appointment->business->brandColor() }}; background: color-mix(in srgb, {{ $appointment->business->brandColor() }} 6%, white); border-color: #e2e8f0;"
                                >
                                    <div class="flex items-center justify-between">
                                        <span class="font-mono text-[10px] font-semibold text-slate-700">{{ $appointment->start_time }}</span>
                                        <span class="rounded-full px-2 py-0.5 text-[9px] font-bold uppercase
                                            @if($appointment->status === 'confirmed') bg-emerald-100 text-emerald-800
                                            @elseif($appointment->status === 'pending') bg-amber-100 text-amber-800
                                            @elseif($appointment->status === 'completed') bg-blue-100 text-blue-800
                                            @else bg-rose-100 text-rose-800 @endif
                                        ">
                                            {{ $appointment->status }}
                                        </span>
                                    </div>
                                    <p class="font-display mt-1 font-semibold text-slate-900 leading-tight">
                                        {{ $appointment->service->name }}
                                    </p>
                                    <p class="text-[11px] text-slate-500">
                                        {{ $appointment->user->name }}
                                    </p>
                                    <div class="mt-2 flex items-center justify-between border-t border-slate-200/60 pt-1.5 text-[10px]">
                                        <span class="font-semibold text-slate-700">${{ number_format((float) $appointment->service_price, 2) }}</span>
                                        <a href="{{ route('appointments.edit', $appointment) }}" class="text-slate-600 underline hover:text-slate-900">
                                            Detalles →
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-lg border border-dashed border-slate-200 py-6 text-center text-[11px] text-slate-400">
                                    Sin citas asignadas
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- View 2: Operational Queue List -->
        <div x-show="viewMode === 'list'" class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="border-b border-slate-200 bg-slate-50/70 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-3 px-4">Fecha & Hora</th>
                            <th class="py-3 px-4">Servicio & Negocio</th>
                            <th class="py-3 px-4">Cliente Titular</th>
                            <th class="py-3 px-4">Total / Anticipo</th>
                            <th class="py-3 px-4">Estado Cita</th>
                            <th class="py-3 px-4">Estado Pago</th>
                            <th class="py-3 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentAppointments as $appointment)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-mono text-xs">
                                    <div class="font-semibold text-slate-900">{{ $appointment->appointment_date?->format('Y-m-d') }}</div>
                                    <div class="text-slate-500">{{ $appointment->start_time }} - {{ $appointment->end_time }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-slate-900">{{ $appointment->service->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $appointment->business->name }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-slate-900">{{ $appointment->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $appointment->user->email }}</div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs">
                                    <div class="font-semibold text-slate-900">${{ number_format((float) $appointment->service_price, 2) }}</div>
                                    <div class="text-emerald-700">Seña: ${{ number_format((float) $appointment->advance_amount, 2) }} ({{ (int) $appointment->advance_percentage }}%)</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize
                                        @if($appointment->status === 'confirmed') bg-emerald-100 text-emerald-800
                                        @elseif($appointment->status === 'pending') bg-amber-100 text-amber-800
                                        @elseif($appointment->status === 'completed') bg-blue-100 text-blue-800
                                        @else bg-rose-100 text-rose-800 @endif
                                    ">
                                        {{ $appointment->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize
                                        @if($appointment->payment_status === 'paid') bg-emerald-100 text-emerald-800
                                        @else bg-amber-100 text-amber-800 @endif
                                    ">
                                        {{ $appointment->payment_status === 'paid' ? 'Anticipo Pagado' : 'Pendiente Seña' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($appointment->user_id === $user->id && $appointment->payment_status !== 'paid' && !in_array($appointment->status, ['cancelled', 'completed']))
                                            <a
                                                href="{{ route('appointments.payment.show', $appointment) }}"
                                                class="rounded-lg bg-emerald-600 px-2.5 py-1 text-xs font-semibold text-white shadow-xs transition hover:bg-emerald-500"
                                            >
                                                Pagar Seña
                                            </a>
                                        @endif

                                        @if (($user->isAdmin() || $user->isBusiness()) && $appointment->status === 'pending')
                                            <form method="POST" action="{{ route('appointments.status', $appointment) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="rounded-lg bg-slate-900 px-2.5 py-1 text-xs font-semibold text-white transition hover:bg-slate-800">
                                                    Confirmar
                                                </button>
                                            </form>
                                        @endif

                                        @if (($user->isAdmin() || $user->isBusiness()) && $appointment->status === 'confirmed')
                                            <form method="POST" action="{{ route('appointments.status', $appointment) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="rounded-lg bg-blue-600 px-2.5 py-1 text-xs font-semibold text-white transition hover:bg-blue-500">
                                                    Completar
                                                </button>
                                            </form>
                                        @endif

                                        <a
                                            href="{{ route('appointments.edit', $appointment) }}"
                                            class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                                        >
                                            Ver
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-sm text-slate-400">
                                    No hay citas recientes registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
