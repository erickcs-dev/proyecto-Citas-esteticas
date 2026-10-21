<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Service;
use App\Services\AppointmentPaymentService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // ======================================================================
        // GUIA 4 - ACTIVIDAD 2: GESTION DE PAYLOAD
        // El servidor transforma las entidades en tramas JSON claras para consumo del cliente.
        // ======================================================================
        $user = $request->user();

        if ($user->isAdmin()) {
            $appointments = Appointment::query()
                ->with(['user', 'business', 'service'])
                ->latest('appointment_date')
                ->latest('start_time')
                ->get();
        } elseif ($user->isBusiness()) {
            $appointments = Appointment::query()
                ->with(['user', 'business', 'service'])
                ->whereIn('business_id', $user->businesses()->pluck('id'))
                ->latest('appointment_date')
                ->latest('start_time')
                ->get();
        } else {
            $appointments = $user->appointments()
                ->with(['business', 'service'])
                ->latest('appointment_date')
                ->latest('start_time')
                ->get();
        }

        return response()->json([
            'data' => $appointments->map(fn (Appointment $appointment) => $this->transform($appointment)),
        ]);
    }

    public function exportReport(Request $request): JsonResponse
    {
        // ======================================================================
        // MANTENIMIENTO EVOLUTIVO: MODULO DE REPORTERIA EXPORTABLE EN JSON
        // Extiende el sistema para exportar un reporte estructurado en JSON con
        // metricas financieras excluyendo citas canceladas del calculo total.
        // ======================================================================
        $user = $request->user();

        if ($user->isAdmin()) {
            $appointments = Appointment::query()
                ->with(['user', 'business', 'service'])
                ->latest('appointment_date')
                ->latest('start_time')
                ->get();
        } elseif ($user->isBusiness()) {
            $appointments = Appointment::query()
                ->with(['user', 'business', 'service'])
                ->whereIn('business_id', $user->businesses()->pluck('id'))
                ->latest('appointment_date')
                ->latest('start_time')
                ->get();
        } else {
            $appointments = $user->appointments()
                ->with(['business', 'service'])
                ->latest('appointment_date')
                ->latest('start_time')
                ->get();
        }

        $activeAppointments = $appointments->reject(
            fn (Appointment $appointment) => $appointment->status === Appointment::STATUS_CANCELLED
        );

        return response()->json([
            'report_type' => 'appointments_summary',
            'generated_at' => now()->toIso8601String(),
            'summary' => [
                'total_records' => $appointments->count(),
                'active_appointments' => $activeAppointments->count(),
                'cancelled_appointments' => $appointments->count() - $activeAppointments->count(),
                'total_service_amount' => round((float) $activeAppointments->sum('service_price'), 2),
                'total_advance_amount' => round((float) $activeAppointments->sum('advance_amount'), 2),
            ],
            'data' => $appointments->map(fn (Appointment $appointment) => $this->transform($appointment))->values(),
        ]);
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated = $this->normalizeAppointmentInput($request, $validated);
        $service = Service::query()->findOrFail($validated['service_id']);
        $paymentData = app(AppointmentPaymentService::class)->buildPaymentData(
            $service,
            $validated['payment_status'] ?? null
        );

        $appointment = DB::transaction(function () use ($request, $validated, $paymentData) {
            $hasOverlap = Appointment::query()
                ->where('business_id', $validated['business_id'])
                ->whereDate('appointment_date', $validated['appointment_date'])
                ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED])
                ->where('start_time', '<', $validated['end_time'])
                ->where('end_time', '>', $validated['start_time'])
                ->lockForUpdate()
                ->exists();

            if ($hasOverlap) {
                throw ValidationException::withMessages([
                    'start_time' => 'Ya existe una cita en ese rango de tiempo.',
                ]);
            }

            return $request->user()->appointments()->create([
                ...$validated,
                ...$paymentData,
            ]);
        });

        $appointment->load(['user', 'business', 'service']);

        // ======================================================================
        // GUIA 1 - ACTIVIDAD 5: DISENO DE PAYLOAD
        // La respuesta JSON devuelve una trama estructurada con los datos principales de la cita.
        // ======================================================================
        return response()->json([
            'message' => 'Cita creada correctamente.',
            'data' => $this->transform($appointment),
        ], 201);
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): JsonResponse
    {
        $this->ensureAppointmentAccess($request, $appointment);
        $this->ensureAppointmentCanBeEdited($request, $appointment);

        $validated = $request->validated();
        $validated = $this->normalizeAppointmentInput($request, $validated, $appointment);
        $service = Service::query()->findOrFail($validated['service_id']);
        $paymentData = app(AppointmentPaymentService::class)->buildPaymentData(
            $service,
            $validated['payment_status'] ?? $appointment->payment_status
        );

        $appointment->update([
            ...$validated,
            ...$paymentData,
        ]);
        $appointment->load(['user', 'business', 'service']);

        return response()->json([
            'message' => 'Cita actualizada correctamente.',
            'data' => $this->transform($appointment),
        ]);
    }

    private function ensureAppointmentAccess(Request $request, Appointment $appointment): void
    {
        $user = $request->user();

        $ownsAppointment = $appointment->user_id === $user->id;
        $ownsBusiness = $user->isAdmin() || $user->businesses()->whereKey($appointment->business_id)->exists();

        abort_unless($ownsAppointment || $ownsBusiness, 403, 'No puedes gestionar esta cita.');
    }

    private function ensureAppointmentCanBeEdited(Request $request, Appointment $appointment): void
    {
        if ($appointment->isClosed()) {
            throw new HttpResponseException(response()->json([
                'message' => 'Las citas canceladas o completadas ya no se pueden editar.',
            ], 422));
        }
    }

    private function normalizeAppointmentInput(Request $request, array $validated, ?Appointment $appointment = null): array
    {
        if ($request->user()->isClient()) {
            $validated['status'] = $appointment?->status === Appointment::STATUS_CONFIRMED
                ? Appointment::STATUS_CONFIRMED
                : Appointment::STATUS_PENDING;

            $validated['payment_status'] = $appointment?->payment_status
                ?? Appointment::PAYMENT_STATUS_PENDING_ADVANCE;
        }

        return $validated;
    }

    private function transform(Appointment $appointment): array
    {
        return [
            'id' => $appointment->id,
            'appointment_date' => optional($appointment->appointment_date)->format('Y-m-d'),
            'start_time' => $appointment->start_time,
            'end_time' => $appointment->end_time,
            'status' => $appointment->status,
            'notes' => $appointment->notes,
            'payment' => [
                'service_price' => $appointment->service_price,
                'advance_percentage' => $appointment->advance_percentage,
                'advance_amount' => $appointment->advance_amount,
                'payment_status' => $appointment->payment_status,
            ],
            'user' => $appointment->relationLoaded('user') && $appointment->user ? [
                'id' => $appointment->user->id,
                'name' => $appointment->user->name,
                'email' => $appointment->user->email,
            ] : null,
            'business' => $appointment->business ? [
                'id' => $appointment->business->id,
                'name' => $appointment->business->name,
                'type' => $appointment->business->type,
            ] : null,
            'service' => $appointment->service ? [
                'id' => $appointment->service->id,
                'name' => $appointment->service->name,
                'duration_minutes' => $appointment->service->duration_minutes,
                'price' => $appointment->service->price,
            ] : null,
        ];
    }
}
