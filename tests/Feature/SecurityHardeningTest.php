<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_update_cannot_delete_images_outside_own_service(): void
    {
        Storage::fake('public');

        $businessOwner = User::factory()->business()->create();
        $business = $businessOwner->businesses()->create([
            'name' => 'Salón Autor',
            'type' => 'estetica',
        ]);

        $service = $business->services()->create([
            'name' => 'Facial Hidratante',
            'duration_minutes' => 60,
            'price' => 50000,
            'active' => true,
            'gallery_images' => ['services/'.$business->id.'/legit.jpg'],
        ]);

        // Crear archivo legítimo del servicio y un archivo ajeno en el disco público
        Storage::disk('public')->put('services/'.$business->id.'/legit.jpg', 'fake-image-content');
        Storage::disk('public')->put('foreign_folder/victim.jpg', 'victim-image-content');

        $response = $this->actingAs($businessOwner)->put(
            route('businesses.services.update', [$business, $service]),
            [
                'name' => 'Facial Hidratante Modificado',
                'duration_minutes' => 60,
                'price' => 50000,
                'active' => true,
                'remove_images' => [
                    'services/'.$business->id.'/legit.jpg',
                    'foreign_folder/victim.jpg', // Intento de borrado IDOR arbitrario
                ],
            ]
        );

        $response->assertRedirect();

        // El archivo legítimo fue eliminado según lo solicitado
        Storage::disk('public')->assertMissing('services/'.$business->id.'/legit.jpg');

        // El archivo ajeno DEBE permanecer intacto (IDOR mitigado)
        Storage::disk('public')->assertExists('foreign_folder/victim.jpg');
    }

    public function test_client_cannot_access_business_management_area(): void
    {
        $client = User::factory()->client()->create();

        $response = $this->actingAs($client)->get(route('businesses.index'));

        // El middleware EnsureBusinessApproved debe denegar el acceso a clientes regulares
        $response->assertForbidden();
    }

    public function test_holiday_sync_api_validates_country_code_format(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->postJson('/api/holidays/sync', [
            'country_code' => '../../malicious',
            'year' => 2026,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['country_code']);
    }

    public function test_security_headers_are_present_on_web_and_api(): void
    {
        $webResponse = $this->get('/');
        $webResponse->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $webResponse->assertHeader('X-Content-Type-Options', 'nosniff');
        $webResponse->assertHeader('X-XSS-Protection', '0');
        $webResponse->assertHeader('Content-Security-Policy');

        $apiResponse = $this->getJson('/api/businesses');
        $apiResponse->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $apiResponse->assertHeader('X-Content-Type-Options', 'nosniff');
        $apiResponse->assertHeader('X-XSS-Protection', '0');
        $apiResponse->assertHeader('Content-Security-Policy');
    }
}
