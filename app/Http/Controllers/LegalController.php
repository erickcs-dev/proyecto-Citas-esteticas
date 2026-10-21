<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class LegalController extends Controller
{
    /**
     * Muestra la Política de Privacidad y Tratamiento de Datos Personales (Ley 1581 / Habeas Data / GDPR).
     */
    public function privacy(): View
    {
        return view('legal.privacy');
    }

    /**
     * Muestra los Términos y Condiciones de Uso del Servicio y Contratación Electrónica (Ley 1480).
     */
    public function terms(): View
    {
        return view('legal.terms');
    }

    /**
     * Muestra la Política de Cookies y Tecnologías de Rastreo.
     */
    public function cookies(): View
    {
        return view('legal.cookies');
    }

    /**
     * Muestra el canal de radicación de Peticiones, Quejas, Reclamos y Sugerencias (PQR).
     */
    public function pqr(): View
    {
        return view('legal.pqr');
    }
}
