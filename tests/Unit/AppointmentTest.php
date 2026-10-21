<?php

namespace Tests\Unit;

use App\Models\Appointment;
use PHPUnit\Framework\TestCase;

class AppointmentTest extends TestCase
{
    /**
     * Prueba unitaria básica para verificar las constantes y estados.
     */
    public function test_appointment_returns_correct_statuses(): void
    {
        // Act
        $statuses = Appointment::statuses();

        // Assert
        $this->assertContains(Appointment::STATUS_PENDING, $statuses);
        $this->assertContains(Appointment::STATUS_CONFIRMED, $statuses);
        $this->assertContains(Appointment::STATUS_CANCELLED, $statuses);
        $this->assertContains(Appointment::STATUS_COMPLETED, $statuses);
        $this->assertCount(4, $statuses);
    }

    public function test_is_closed_returns_true_for_completed_or_cancelled(): void
    {
        // Arrange
        $completedAppointment = new Appointment(['status' => Appointment::STATUS_COMPLETED]);
        $cancelledAppointment = new Appointment(['status' => Appointment::STATUS_CANCELLED]);
        $pendingAppointment = new Appointment(['status' => Appointment::STATUS_PENDING]);

        // Assert
        $this->assertTrue($completedAppointment->isClosed());
        $this->assertTrue($cancelledAppointment->isClosed());
        $this->assertFalse($pendingAppointment->isClosed());
    }
}
