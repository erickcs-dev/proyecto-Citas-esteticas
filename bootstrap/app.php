<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

use App\Http\Middleware\EnsureBusinessApproved;
use App\Http\Middleware\EnsureProfileCompleted;
use App\Http\Middleware\SecurityHeadersMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // ======================================================================
        // GUIA 1 - ACTIVIDAD 1: ESCENARIO DE SOFTWARE
        // El proyecto expone interfaces web y API para una arquitectura distribuida cliente-servidor.
        // ======================================================================
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ======================================================================
        // GUIA 2 - ACTIVIDAD 1: INTEROPERABILIDAD
        // El middleware actua como capa transversal para mantener transparencia entre peticiones web y API.
        $trustedProxies = env('TRUSTED_PROXIES');
        if (! empty($trustedProxies)) {
            $middleware->trustProxies(at: $trustedProxies === '*' ? '*' : array_values(array_filter(array_map('trim', explode(',', $trustedProxies)))));
        }

        $middleware->web(append: [
            SecurityHeadersMiddleware::class,
        ]);

        $middleware->api(append: [
            SecurityHeadersMiddleware::class,
        ]);

        $middleware->alias([
            'profile.completed' => EnsureProfileCompleted::class,
            'business.approved' => EnsureBusinessApproved::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
