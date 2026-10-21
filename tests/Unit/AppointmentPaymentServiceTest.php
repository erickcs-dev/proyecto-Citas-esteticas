<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Codificacion y Pruebas del Software
 */

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Service;
use App\Services\AppointmentPaymentService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AppointmentPaymentServiceTest extends TestCase
{
    /**
     * Prueba puramente unitaria que evalúa la lógica de cálculo
     * sin necesidad de tocar la base de datos.
     */
    public function test_build_payment_data_calculates_advance_correctly(): void
    {
        // Arrange
        $service = new Service(['price' => 150000]);
        $paymentService = new AppointmentPaymentService;

        // Act
        $data = $paymentService->buildPaymentData($service);

        // Assert
        $this->assertEquals(150000.00, $data['service_price']);
        $this->assertEquals(50.00, $data['advance_percentage']);
        $this->assertEquals(75000.00, $data['advance_amount']);
        $this->assertEquals(Appointment::PAYMENT_STATUS_PENDING_ADVANCE, $data['payment_status']);
    }

    public function test_build_payment_data_preserves_custom_payment_status(): void
    {
        // Arrange
        $service = new Service(['price' => 200000]);
        $paymentService = new AppointmentPaymentService;

        // Act
        $data = $paymentService->buildPaymentData($service, Appointment::PAYMENT_STATUS_PAID);

        // Assert
        $this->assertEquals(Appointment::PAYMENT_STATUS_PAID, $data['payment_status']);
        $this->assertEquals(100000.00, $data['advance_amount']);
    }

    public function test_build_payment_data_rejects_negative_or_zero_service_price(): void
    {
        $service = new Service(['price' => -50000]);
        $paymentService = new AppointmentPaymentService;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El precio del servicio debe ser mayor a cero.');

        $paymentService->buildPaymentData($service);
    }

    public function test_validate_defensive_transaction_accepts_valid_payload(): void
    {
        $paymentService = new AppointmentPaymentService;

        $payload = $paymentService->validateTransactionPayload(
            email: 'cliente@fesc.edu.co',
            amount: 75000.00,
            reference: 'PSE-2026-001'
        );

        $this->assertSame('cliente@fesc.edu.co', $payload['email']);
        $this->assertSame(75000.00, $payload['amount']);
        $this->assertSame('PSE-2026-001', $payload['reference']);
    }

    public function test_validate_defensive_transaction_rejects_malformed_email(): void
    {
        $paymentService = new AppointmentPaymentService;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El correo electronico del pagador no tiene un formato valido.');

        $paymentService->validateTransactionPayload(
            email: 'correo-invalido@@dominio',
            amount: 75000.00,
            reference: 'PSE-2026-001'
        );
    }

    public function test_validate_defensive_transaction_rejects_non_positive_amount(): void
    {
        $paymentService = new AppointmentPaymentService;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El monto del pago debe ser estrictamente mayor a cero.');

        $paymentService->validateTransactionPayload(
            email: 'cliente@fesc.edu.co',
            amount: 0,
            reference: 'PSE-2026-001'
        );
    }

    public function test_validate_defensive_transaction_rejects_disallowed_characters_in_reference(): void
    {
        $paymentService = new AppointmentPaymentService;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La referencia de pago contiene caracteres no permitidos.');

        $paymentService->validateTransactionPayload(
            email: 'cliente@fesc.edu.co',
            amount: 75000.00,
            reference: "PSE-001'; DROP TABLE appointments; --"
        );
    }
}
