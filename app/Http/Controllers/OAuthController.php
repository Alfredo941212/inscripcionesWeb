<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OAuthController extends Controller
{
    public function redirectGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
        $googleUser = Socialite::driver('google')->user();

        // Buscar si ya existe
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Crear usuario nuevo
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(uniqid()), // no se necesita
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
