<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Service;
use App\Services\AppointmentPaymentService;
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
}
