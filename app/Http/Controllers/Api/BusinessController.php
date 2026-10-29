<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class BusinessController extends Controller
{
    public function index(): JsonResponse
    {
        $businesses = Business::query()
            ->whereHas('user', function ($query): void {
                $query->where('role', User::ROLE_ADMIN)
                    ->orWhereNotNull('business_approved_at');
            })
            ->withCount(['services', 'appointments', 'hours'])
            ->orderBy('name')
            ->get()
            ->map(fn (Business $business) => [
                'id' => $business->id,
                'name' => $business->name,
                'type' => $business->type,
                'phone' => $business->phone,
                'email' => $business->email,
                'address' => $business->address,
                'services_count' => $business->services_count,
                'appointments_count' => $business->appointments_count,
                'hours_count' => $business->hours_count,
            ]);

        return response()->json([
            'data' => $businesses,
        ]);
    }

    public function show(Business $business): JsonResponse
    {
        abort_unless(
            $business->user()
                ->where(function ($query): void {
                    $query->where('role', User::ROLE_ADMIN)
                        ->orWhereNotNull('business_approved_at');
                })
                ->exists(),
            404
        );

        $business->load(['services', 'hours']);

        return response()->json([
            'data' => [
                'id' => $business->id,
                'name' => $business->name,
                'type' => $business->type,
                'phone' => $business->phone,
                'email' => $business->email,
                'address' => $business->address,
                'services' => $business->services->map(fn ($service) => [
                    'id' => $service->id,
                    'name' => $service->name,
                    'duration_minutes' => $service->duration_minutes,
                    'price' => $service->price,
                    'active' => $service->active,
                ])->values(),
                'hours' => $business->hours->map(fn ($hour) => [
                    'day_of_week' => $hour->day_of_week,
                    'opens_at' => $hour->opens_at,
                    'closes_at' => $hour->closes_at,
                    'is_active' => $hour->is_active,
                ])->values(),
            ],
        ]);
    }
}
