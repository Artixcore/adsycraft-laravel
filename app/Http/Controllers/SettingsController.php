<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\UpdateEmailRequest;
use App\Http\Requests\Settings\UpdateNotificationsRequest;
use App\Http\Requests\Settings\UpdatePasswordRequest;
use App\Http\Requests\Settings\UpdatePreferencesRequest;
use App\Http\Requests\Settings\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('dashboard.settings.index', [
            'user' => auth()->user(),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->name = $request->validated('name');
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }
        $user->save();

        return response()->json(['ok' => true, 'message' => 'Profile updated successfully.']);
    }

    public function updateEmail(UpdateEmailRequest $request): JsonResponse
    {
        $request->user()->update(['email' => $request->validated('email')]);

        return response()->json(['ok' => true, 'message' => 'Email updated successfully.']);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return response()->json(['ok' => true, 'message' => 'Password updated successfully.']);
    }

    public function updatePreferences(UpdatePreferencesRequest $request): JsonResponse
    {
        $user = $request->user();
        $prefs = $user->preferences ?? [];
        $prefs['timezone'] = $request->validated('timezone');
        $prefs['language'] = $request->validated('language');
        $prefs['theme'] = $request->validated('theme');
        $user->preferences = $prefs;
        $user->save();

        return response()->json(['ok' => true, 'message' => 'Preferences updated successfully.']);
    }

    public function updateNotifications(UpdateNotificationsRequest $request): JsonResponse
    {
        $user = $request->user();
        $prefs = $user->preferences ?? [];
        $prefs['notify_posts'] = $request->boolean('notify_posts');
        $prefs['notify_weekly'] = $request->boolean('notify_weekly');
        $user->preferences = $prefs;
        $user->save();

        return response()->json(['ok' => true, 'message' => 'Notification preferences updated.']);
    }

    public function twoFactorRecoveryCodes(): View|\Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        if (! $user->two_factor_recovery_codes) {
            return redirect()->route('dashboard.settings')->with('error', 'No recovery codes found.');
        }
        $codes = json_decode(decrypt($user->two_factor_recovery_codes), true);

        return view('dashboard.settings.two-factor-recovery-codes', ['codes' => $codes]);
    }

    public function twoFactorSetup(): View
    {
        return view('dashboard.settings.two-factor-setup', [
            'user' => auth()->user(),
        ]);
    }

    public function logoutOtherSessions(Request $request): JsonResponse
    {
        $request->validate(['password' => ['required', 'current_password']]);
        Auth::logoutOtherDevices($request->password);

        return response()->json(['ok' => true, 'message' => 'Other sessions have been logged out.']);
    }
}
