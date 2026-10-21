<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBusinessApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->needsBusinessApproval()) {
            return redirect()->route('business-access.pending');
        }

        if (! $user->canManageBusinesses()) {
            abort(403, 'Acceso exclusivo para negocios aprobados y administradores.');
        }

        return $next($request);
    }
}
