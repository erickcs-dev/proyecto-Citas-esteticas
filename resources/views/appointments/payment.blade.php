@extends('layouts.app')

@section('title', 'Checkout & Confirmación de Cita - '.$appointment->service->name)
@section('theme_style', '--primary-color: '.$appointment->business->brandColor().';')
@section('hide_errors')
@section('hide_flash')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8" x-data="{ currentMethod: '{{ old('payment_method', 'credit_card') }}', termsAccepted: false }">
    <!-- Breadcrumb & Step Indicator -->
    <div class="mb-8 border-b border-slate-200 pb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-slate-700">
                    <span class="h-2 w-2 rounded-full" style="background-color: var(--primary-color);"></span>
                    Checkout Seguro · Stitch UI
                </span>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-medium text-slate-500">Reserva #{{ $appointment->id }}</span>
            </div>
            <a href="{{ route('appointments.edit', $appointment) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-slate-900 transition">
                <x-heroicon-o-arrow-left class="h-4 w-4" />
                Volver a la cita
            </a>
        </div>

        <h1 class="font-display mt-3 text-3xl font-semibold tracking-tight text-slate-900 md:text-4xl">
            Confirmación y Pago de Anticipo (50%)
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Asegura tu turno bloqueando la agenda del especialista mediante el pago de la seña reglamentaria.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800">
            <div class="flex items-center gap-2">
                <x-heroicon-s-check-circle class="h-5 w-5 text-emerald-600" />
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
        <!-- Left Column: Payment Form & Checkout (7 cols) -->
        <div class="space-y-6 lg:col-span-7">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-base font-semibold text-slate-900">Método de Pago Seguro</h2>
                    <p class="text-xs text-slate-500">Selecciona el canal para registrar tu anticipo de confirmación.</p>
                </div>

                <form method="POST" action="{{ route('appointments.payment.process', $appointment) }}" class="mt-5 space-y-6">
                    @csrf

                    <!-- Visual Method Selector (Radio Cards) -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Canal de Pago</label>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <button
                                type="button"
                                @click="currentMethod = 'credit_card'; document.getElementById('payment_method').value = 'credit_card'; updatePaymentFields();"
                                class="flex flex-col items-center justify-center rounded-xl border p-3 text-center transition"
                                :class="currentMethod === 'credit_card' ? 'border-slate-900 bg-slate-50 ring-2 ring-slate-900/10' : 'border-slate-200 bg-white hover:bg-slate-50'"
                            >
                                <x-heroicon-o-credit-card class="h-6 w-6 text-slate-700" />
                                <span class="mt-1.5 text-xs font-semibold text-slate-900">Tarjeta Crédito</span>
                            </button>

                            <button
                                type="button"
                                @click="currentMethod = 'debit_card'; document.getElementById('payment_method').value = 'debit_card'; updatePaymentFields();"
                                class="flex flex-col items-center justify-center rounded-xl border p-3 text-center transition"
                                :class="currentMethod === 'debit_card' ? 'border-slate-900 bg-slate-50 ring-2 ring-slate-900/10' : 'border-slate-200 bg-white hover:bg-slate-50'"
                            >
                                <x-heroicon-o-credit-card class="h-6 w-6 text-slate-700" />
                                <span class="mt-1.5 text-xs font-semibold text-slate-900">Tarjeta Débito</span>
                            </button>

                            <button
                                type="button"
                                @click="currentMethod = 'nequi'; document.getElementById('payment_method').value = 'nequi'; updatePaymentFields();"
                                class="flex flex-col items-center justify-center rounded-xl border p-3 text-center transition"
                                :class="currentMethod === 'nequi' ? 'border-slate-900 bg-slate-50 ring-2 ring-slate-900/10' : 'border-slate-200 bg-white hover:bg-slate-50'"
                            >
                                <x-heroicon-o-device-phone-mobile class="h-6 w-6 text-purple-700" />
                                <span class="mt-1.5 text-xs font-semibold text-slate-900">Nequi</span>
                            </button>

                            <button
                                type="button"
                                @click="currentMethod = 'bancolombia_transfer'; document.getElementById('payment_method').value = 'bancolombia_transfer'; updatePaymentFields();"
                                class="flex flex-col items-center justify-center rounded-xl border p-3 text-center transition"
                                :class="currentMethod === 'bancolombia_transfer' ? 'border-slate-900 bg-slate-50 ring-2 ring-slate-900/10' : 'border-slate-200 bg-white hover:bg-slate-50'"
                            >
                                <x-heroicon-o-building-library class="h-6 w-6 text-amber-600" />
                                <span class="mt-1.5 text-xs font-semibold text-slate-900">Bancolombia</span>
                            </button>
                        </div>

                        <!-- Native Select for fallback and form binding -->
                        <div class="mt-3">
                            <select
                                id="payment_method"
                                name="payment_method"
                                x-model="currentMethod"
                                required
                                class="block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs text-slate-700 shadow-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                            >
                                <option value="">Selecciona una opción</option>
                                @foreach ($paymentMethods as $key => $label)
                                    <option value="{{ $key }}" @selected(old('payment_method') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Common Payer Fields -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="account_holder" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Titular de la Cuenta / Tarjeta</label>
                            <input
                                id="account_holder"
                                name="account_holder"
                                type="text"
                                value="{{ old('account_holder', $appointment->user->name) }}"
                                required
                                class="mt-1.5 block w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                            >
                        </div>

                        <div>
                            <label for="reference" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Referencia / Comprobante</label>
                            <input
                                id="reference"
                                name="reference"
                                type="text"
                                value="{{ old('reference') }}"
                                placeholder="Ej: AP-{{ strtoupper(Str::random(6)) }}"
                                required
                                class="mt-1.5 block w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-mono text-slate-900 shadow-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                            >
                        </div>
                    </div>

                    <!-- Method-Specific Dynamic Fields -->
                    <!-- Nequi Block -->
                    <div id="nequi-fields" class="method-fields rounded-xl border border-purple-200 bg-purple-50/50 p-4">
                        <label for="phone_number" class="block text-xs font-semibold uppercase tracking-wider text-purple-900">Número de Celular Nequi</label>
                        <input
                            id="phone_number"
                            name="phone_number"
                            type="text"
                            value="{{ old('phone_number') }}"
                            placeholder="300 123 4567"
                            class="mt-1.5 block w-full rounded-xl border border-purple-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-purple-600 focus:ring-1 focus:ring-purple-600"
                        >
                        <p class="mt-1.5 text-[11px] text-purple-700">Recibirás una notificación push en tu app Nequi para autorizar el débito.</p>
                    </div>

                    <!-- Bancolombia Block -->
                    <div id="bancolombia_transfer-fields" class="method-fields rounded-xl border border-amber-200 bg-amber-50/50 p-4">
                        <label for="account_number" class="block text-xs font-semibold uppercase tracking-wider text-amber-900">Número de Cuenta Bancolombia</label>
                        <input
                            id="account_number"
                            name="account_number"
                            type="text"
                            value="{{ old('account_number') }}"
                            placeholder="Ahorros o corriente (11 dígitos)"
                            class="mt-1.5 block w-full rounded-xl border border-amber-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-amber-600 focus:ring-1 focus:ring-amber-600"
                        >
                        <p class="mt-1.5 text-[11px] text-amber-800">Transfiere a la cuenta comercial y escribe el número de comprobante arriba.</p>
                    </div>

                    <!-- Credit Card Block -->
                    <div id="credit_card-fields" class="method-fields rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="flex items-center justify-between border-b border-slate-200/80 pb-3 mb-3">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-700">Datos de Tarjeta de Crédito</span>
                            <span class="flex items-center gap-1 text-[11px] font-semibold text-emerald-700">
                                <x-heroicon-s-lock-closed class="h-3.5 w-3.5" />
                                Cifrado Tokenizado PCI-DSS
                            </span>
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="card_number" class="block text-xs text-slate-600">Número de Tarjeta</label>
                                <input
                                    id="card_number"
                                    name="card_number"
                                    type="text"
                                    placeholder="•••• •••• •••• 4242"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-mono text-slate-900 shadow-sm"
                                >
                            </div>

                            <div class="sm:col-span-2">
                                <label for="card_name" class="block text-xs text-slate-600">Nombre impreso en tarjeta</label>
                                <input
                                    id="card_name"
                                    name="card_name"
                                    type="text"
                                    placeholder="NOMBRE Y APELLIDO"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm uppercase text-slate-900 shadow-sm"
                                >
                            </div>

                            <div>
                                <label for="expiry_date" class="block text-xs text-slate-600">Vencimiento (MM/AA)</label>
                                <input
                                    id="expiry_date"
                                    name="expiry_date"
                                    type="text"
                                    placeholder="12/28"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-mono text-slate-900 shadow-sm"
                                >
                            </div>

                            <div>
                                <label for="cvv" class="block text-xs text-slate-600">Código CVV</label>
                                <input
                                    id="cvv"
                                    name="cvv"
                                    type="password"
                                    maxlength="4"
                                    placeholder="•••"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-mono text-slate-900 shadow-sm"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Debit Card Block -->
                    <div id="debit_card-fields" class="method-fields rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="flex items-center justify-between border-b border-slate-200/80 pb-3 mb-3">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-700">Datos de Tarjeta Débito</span>
                            <span class="flex items-center gap-1 text-[11px] font-semibold text-emerald-700">
                                <x-heroicon-s-lock-closed class="h-3.5 w-3.5" />
                                Transacción Segura
                            </span>
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="debit_card_number" class="block text-xs text-slate-600">Número de Tarjeta Débito</label>
                                <input
                                    id="debit_card_number"
                                    type="text"
                                    placeholder="•••• •••• •••• 5555"
                                    data-mirror="card_number"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-mono text-slate-900 shadow-sm"
                                >
                            </div>

                            <div class="sm:col-span-2">
                                <label for="debit_card_name" class="block text-xs text-slate-600">Nombre impreso</label>
                                <input
                                    id="debit_card_name"
                                    type="text"
                                    placeholder="NOMBRE Y APELLIDO"
                                    data-mirror="card_name"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm uppercase text-slate-900 shadow-sm"
                                >
                            </div>

                            <div>
                                <label for="debit_expiry_date" class="block text-xs text-slate-600">Vencimiento</label>
                                <input
                                    id="debit_expiry_date"
                                    type="text"
                                    placeholder="12/28"
                                    data-mirror="expiry_date"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-mono text-slate-900 shadow-sm"
                                >
                            </div>

                            <div>
                                <label for="debit_cvv" class="block text-xs text-slate-600">CVV</label>
                                <input
                                    id="debit_cvv"
                                    type="password"
                                    maxlength="4"
                                    placeholder="•••"
                                    data-mirror="cvv"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-mono text-slate-900 shadow-sm"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Commercial Cancellation Policy Consent (Ley 1480 / Estatuto del Consumidor) -->
                    <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                name="cancellation_policy_accepted"
                                value="1"
                                required
                                class="mt-1 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                            >
                            <span class="text-xs leading-relaxed text-slate-600">
                                <strong>Política de Cancelación Comercial:</strong> Entiendo que la seña del 50% ($${{ number_format((float) $appointment->advance_amount, 2) }}) reserva y bloquea la agenda profesional. Conforme al Estatuto del Consumidor (Ley 1480), podré reprogramar o solicitar reembolso íntegro notificando con al menos 24 horas de anticipación a la fecha de la cita.
                            </span>
                        </label>
                    </div>

                    @if ($errors->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs text-rose-800">
                            <strong class="font-semibold">Revisa los siguientes campos:</strong>
                            <ul class="mt-1 list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between border-t border-slate-100 pt-5">
                        <a href="{{ route('appointments.edit', $appointment) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                            Cancelar y volver
                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl px-6 py-3.5 text-sm font-semibold text-white shadow-md transition hover:opacity-95"
                            style="background-color: var(--primary-color);"
                        >
                            <x-heroicon-o-lock-closed class="h-4 w-4" />
                            Confirmar Cita y Pagar Seña (${{ number_format((float) $appointment->advance_amount, 2) }})
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Appointment Summary & Guarantee (5 cols) -->
        <div class="lg:col-span-5">
            <div class="sticky top-6 space-y-6">
                <!-- Appointment Card -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Resumen de la Cita</span>
                    <h3 class="font-display mt-2 text-2xl font-bold text-slate-900">
                        {{ $appointment->service->name }}
                    </h3>
                    <p class="text-xs text-slate-500">{{ $appointment->business->name }} · {{ $appointment->business->address ?: 'Sede principal' }}</p>

                    <div class="mt-5 space-y-3 rounded-xl border border-slate-100 bg-slate-50/70 p-4 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Fecha agendada</span>
                            <span class="font-semibold text-slate-900">{{ $appointment->appointment_date?->format('Y-m-d') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Franja horaria</span>
                            <span class="font-mono font-semibold text-slate-900">{{ $appointment->start_time }} - {{ $appointment->end_time }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Duración estimada</span>
                            <span class="font-semibold text-slate-900">{{ $appointment->service->duration_minutes }} minutos</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Titular</span>
                            <span class="font-semibold text-slate-900">{{ $appointment->user->name }}</span>
                        </div>
                    </div>

                    <!-- Breakdown Numbers -->
                    <div class="mt-5 space-y-2.5 border-t border-slate-100 pt-5 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Valor total del tratamiento</span>
                            <span class="font-mono font-semibold text-slate-900">${{ number_format((float) $appointment->service_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between rounded-lg bg-emerald-50 px-3 py-2 text-emerald-800 font-semibold">
                            <span>Anticipo requerido (50%)</span>
                            <span class="font-mono font-bold">${{ number_format((float) $appointment->advance_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>Saldo a liquidar en clínica</span>
                            <span class="font-mono">${{ number_format((float) ($appointment->service_price - $appointment->advance_amount), 2) }}</span>
                        </div>
                    </div>

                    <!-- Status Explanation -->
                    <div class="mt-5 rounded-xl border border-blue-100 bg-blue-50/60 p-3.5 text-xs text-blue-900">
                        <p class="font-semibold">Confirmación Inmediata</p>
                        <p class="mt-0.5 text-[11px] text-blue-700 leading-relaxed">
                            Al registrar el pago de este anticipo, el estado de tu cita cambiará automáticamente de <strong>Pendiente</strong> a <strong>Confirmada</strong>.
                        </p>
                    </div>
                </div>

                <!-- Trust and Legal Guarantees Card -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Garantías del Servicio</h4>
                    
                    <div class="flex items-start gap-3 text-xs text-slate-600">
                        <x-heroicon-s-shield-check class="h-5 w-5 text-emerald-600 shrink-0" />
                        <div>
                            <strong class="text-slate-900">Cumplimiento Estricto PCI-DSS</strong>
                            <p class="mt-0.5 text-[11px] text-slate-500">No almacenamos datos sensibles de tarjeta en texto plano. Transacciones protegidas por tokenización bancaria.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 text-xs text-slate-600">
                        <x-heroicon-s-scale class="h-5 w-5 text-blue-600 shrink-0" />
                        <div>
                            <strong class="text-slate-900">Protección Ley 1480 (Estatuto Consumidor)</strong>
                            <p class="mt-0.5 text-[11px] text-slate-500">Derecho a reprogramación y reembolso de seña en caso de fuerza mayor o notificación previa de 24h.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 text-xs text-slate-600">
                        <x-heroicon-s-lock-closed class="h-5 w-5 text-slate-700 shrink-0" />
                        <div>
                            <strong class="text-slate-900">Privacidad y Habeas Data (Ley 1581)</strong>
                            <p class="mt-0.5 text-[11px] text-slate-500">Tus datos personales y de contacto solo son usados para la coordinación de tu cita.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const paymentMethodSelect = document.getElementById('payment_method');
        const methodBlocks = document.querySelectorAll('.method-fields');
        const debitMirrors = document.querySelectorAll('[data-mirror]');

        function syncDebitMirrors() {
            debitMirrors.forEach((input) => {
                input.addEventListener('input', () => {
                    const target = document.getElementById(input.dataset.mirror);
                    if (target) {
                        target.value = input.value;
                    }
                });
            });
        }

        function updatePaymentFields() {
            const selectedMethod = paymentMethodSelect.value;
            methodBlocks.forEach((block) => {
                block.classList.toggle('hidden', block.id !== `${selectedMethod}-fields`);
                block.classList.toggle('block', block.id === `${selectedMethod}-fields`);
            });
        }

        if (paymentMethodSelect) {
            paymentMethodSelect.addEventListener('change', updatePaymentFields);
        }
        syncDebitMirrors();
        updatePaymentFields();
    </script>
@endpush
