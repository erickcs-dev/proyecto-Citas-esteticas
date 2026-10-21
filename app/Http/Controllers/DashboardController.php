<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Business;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $pendingBusinessRequests = collect();

        $stats = [
            'businesses' => 0,
            'services' => 0,
            'appointments' => 0,
            'pending_appointments' => 0,
            'confirmed_appointments' => 0,
            'today_appointments' => 0,
            'total_advance_collected' => 0,
        ];

        $today = now()->toDateString();
        $appointmentsQuery = Appointment::query()->with(['business', 'service', 'user']);

        if ($user->isAdmin()) {
            $stats['businesses'] = Business::count();
            $stats['services'] = Service::count();
            $stats['appointments'] = Appointment::count();
            $stats['pending_appointments'] = Appointment::where('status', Appointment::STATUS_PENDING)->count();
            $stats['confirmed_appointments'] = Appointment::where('status', Appointment::STATUS_CONFIRMED)->count();
            $stats['today_appointments'] = Appointment::whereDate('appointment_date', $today)->count();
            $stats['total_advance_collected'] = (float) Appointment::where('payment_status', Appointment::PAYMENT_STATUS_PAID)->sum('advance_amount');

            $pendingBusinessRequests = User::query()
                ->whereNotNull('business_requested_at')
                ->whereNull('business_approved_at')
                ->latest('business_requested_at')
                ->get();
            $userBusinesses = Business::latest()->limit(5)->get();
        } elseif ($user->isBusiness()) {
            $businessIds = $user->businesses()->pluck('id');

            $stats['businesses'] = $businessIds->count();
            $stats['services'] = Service::whereIn('business_id', $businessIds)->count();
            $stats['appointments'] = Appointment::whereIn('business_id', $businessIds)->count();
            $stats['pending_appointments'] = Appointment::whereIn('business_id', $businessIds)
                ->where('status', Appointment::STATUS_PENDING)
                ->count();
            $stats['confirmed_appointments'] = Appointment::whereIn('business_id', $businessIds)
                ->where('status', Appointment::STATUS_CONFIRMED)
                ->count();
            $stats['today_appointments'] = Appointment::whereIn('business_id', $businessIds)
                ->whereDate('appointment_date', $today)
                ->count();
            $stats['total_advance_collected'] = (float) Appointment::whereIn('business_id', $businessIds)
                ->where('payment_status', Appointment::PAYMENT_STATUS_PAID)
                ->sum('advance_amount');

            $appointmentsQuery->whereIn('business_id', $businessIds);
            $userBusinesses = $user->businesses()->latest()->get();
        } else {
            $stats['appointments'] = $user->appointments()->count();
            $stats['pending_appointments'] = $user->appointments()
                ->where('status', Appointment::STATUS_PENDING)
                ->count();
            $stats['confirmed_appointments'] = $user->appointments()
                ->where('status', Appointment::STATUS_CONFIRMED)
                ->count();
            $stats['today_appointments'] = $user->appointments()
                ->whereDate('appointment_date', $today)
                ->count();
            $stats['total_advance_collected'] = (float) $user->appointments()
                ->where('payment_status', Appointment::PAYMENT_STATUS_PAID)
                ->sum('advance_amount');

            $appointmentsQuery->where('user_id', $user->id);
            $userBusinesses = collect();
        }

        $recentAppointments = $appointmentsQuery->latest('appointment_date')->latest('start_time')->limit(12)->get();

        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'pendingBusinessRequests' => $pendingBusinessRequests,
            'recentAppointments' => $recentAppointments,
            'userBusinesses' => $userBusinesses,
        ]);
    }
}
