<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', __('Unable to sign in with Google. Please try again.'));
        }

        $user = User::query()->where('google_id', $googleUser->getId())->first();

        if ($user) {
            $user->update([
                'last_login_at' => now(),
                'avatar' => $googleUser->getAvatar(),
            ]);
            Auth::login($user);

            return redirect()->intended(route('dashboard'));
        }

        $existingUser = User::query()->where('email', $googleUser->getEmail())->first();

        if ($existingUser) {
            $existingUser->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'provider' => 'google',
                'last_login_at' => now(),
            ]);
            Auth::login($existingUser);

            return redirect()->intended(route('dashboard'));
        }

        $user = User::create([
            'name' => $googleUser->getName() ?? $googleUser->getEmail(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'provider' => 'google',
            'password' => bcrypt(str()->random(32)),
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return redirect()->intended(route('onboarding'));
    }
}
