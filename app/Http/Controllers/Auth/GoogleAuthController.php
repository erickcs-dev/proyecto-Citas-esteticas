<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AuthRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        $driver = Socialite::driver('google');

        if (app()->environment('local')) {
            $driver = $driver->stateless();
        }

        return $driver->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->filled('error')) {
            return redirect()
                ->route('login')
                ->with('error', 'Google devolvio un error: '.$request->string('error_description', $request->string('error'))->toString());
        }

        if (! $request->filled('code') && ! app()->environment('testing')) {
            return redirect()
                ->route('login')
                ->with('error', 'Google no devolvio el codigo de autorizacion. Verifica la URI autorizada y vuelve a intentarlo desde el boton de acceso.');
        }

        try {
            $driver = Socialite::driver('google');

            if (app()->environment('local')) {
                $driver = $driver->stateless();
            }

            $googleUser = $driver->user();
        } catch (InvalidStateException $exception) {
            abort_unless(app()->environment('local'), 500, $exception->getMessage());

            $googleUser = Socialite::driver('google')->stateless()->user();
        }

        $email = Str::lower((string) $googleUser->getEmail());

        abort_if($email === '', 422, 'Google no devolvio un correo valido.');

        $user = User::query()
            ->where('auth_provider', 'google')
            ->where('provider_id', (string) $googleUser->getId())
            ->first();

        if (! $user) {
            $existingUser = User::query()->where('email', $email)->first();

            if ($existingUser) {
                // Mitigación Pre-Account Takeover:
                // Si la cuenta local previa no había verificado su correo electrónico, quien registró la contraseña
                // original nunca demostró ser el dueño del correo. Al iniciar sesión vía Google (proveedor que sí
                // verifica propiedad del buzón), invalidamos la contraseña previa para evitar que un atacante
                // que conozca la contraseña antigua mantenga acceso a la cuenta.
                $updates = [
                    'name' => $googleUser->getName() ?: $existingUser->name,
                    'email' => $email,
                    'avatar' => $googleUser->getAvatar() ?: $existingUser->avatar,
                    'auth_provider' => 'google',
                    'provider_id' => (string) $googleUser->getId(),
                    'email_verified_at' => $existingUser->email_verified_at ?: now(),
                ];

                if ($existingUser->email_verified_at === null) {
                    $updates['password'] = Hash::make(Str::password(32));
                }

                $existingUser->forceFill($updates)->save();
                $user = $existingUser;
            }
        }

        if ($user) {
            $user->forceFill([
                'name' => $googleUser->getName() ?: $user->name,
                'email' => $email,
                'avatar' => $googleUser->getAvatar() ?: $user->avatar,
                'auth_provider' => 'google',
                'provider_id' => (string) $googleUser->getId(),
                'email_verified_at' => $user->email_verified_at ?: now(),
            ])->save();
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: 'Nuevo usuario',
                'email' => $email,
                'phone' => null,
                'role' => User::ROLE_CLIENT,
                'password' => Hash::make(Str::password()),
                'avatar' => $googleUser->getAvatar(),
                'auth_provider' => 'google',
                'provider_id' => (string) $googleUser->getId(),
                'email_verified_at' => now(),
                'profile_completed_at' => null,
                'business_requested_at' => null,
                'business_approved_at' => null,
            ]);
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->route(AuthRedirect::routeFor($user));
    }
}
