<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Arquitectura y Diseno de Software / Codificacion y Pruebas del Software
 */

namespace App\Services;

use App\Models\Appointment;
use App\Models\Service;
use InvalidArgumentException;

class AppointmentPaymentService
{
    private const SAFE_REFERENCE_PATTERN = '/^[A-Za-z0-9._-]{3,60}$/';

    // ======================================================================
    // GUIA 4 - ACTIVIDAD 1: ATRIBUTOS DE CALIDAD Y CODIFICACION DEFENSIVA
    // El calculo del adelanto se centraliza y valida montos positivos para
    // prevenir estados financieros inconsistentes o valores negativos.
    // ======================================================================
    public function buildPaymentData(Service $service, ?string $paymentStatus = null): array
    {
        $servicePrice = round((float) $service->price, 2);

        if ($servicePrice <= 0) {
            throw new InvalidArgumentException('El precio del servicio debe ser mayor a cero.');
        }

        $advancePercentage = 50.00;
        $advanceAmount = round($servicePrice * ($advancePercentage / 100), 2);

        return [
            'service_price' => $servicePrice,
            'advance_percentage' => $advancePercentage,
            'advance_amount' => $advanceAmount,
            'payment_status' => $paymentStatus ?: Appointment::PAYMENT_STATUS_PENDING_ADVANCE,
        ];
    }

    /**
     * Valida defensivamente los datos de una transacción de pago (correos, montos > 0
     * y referencias libres de caracteres de inyección).
     *
     * @return array{email: string, amount: float, reference: string}
     */
    public function validateTransactionPayload(string $email, float $amount, string $reference): array
    {
        $normalizedEmail = strtolower(trim($email));

        if ($normalizedEmail === '' || filter_var($normalizedEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('El correo electronico del pagador no tiene un formato valido.');
        }

        if ($amount <= 0) {
            throw new InvalidArgumentException('El monto del pago debe ser estrictamente mayor a cero.');
        }

        $normalizedReference = trim($reference);

        if (preg_match(self::SAFE_REFERENCE_PATTERN, $normalizedReference) !== 1) {
            throw new InvalidArgumentException('La referencia de pago contiene caracteres no permitidos.');
        }

        return [
            'email' => $normalizedEmail,
            'amount' => round($amount, 2),
            'reference' => $normalizedReference,
        ];
    }
}
