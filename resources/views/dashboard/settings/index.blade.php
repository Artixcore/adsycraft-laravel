@extends('layouts.app')

@push('vite')
    @vite(['resources/js/settings.js'])
@endpush

@section('content')
<div class="max-w-4xl mx-auto" id="settings-page">
    <x-ui.page-header title="Settings" description="Manage your account and preferences" />

    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
        <nav class="flex sm:flex-col gap-1 min-w-[180px]" aria-label="Settings sections">
            <button type="button" data-settings-tab="profile" class="settings-tab text-left px-4 py-2.5 rounded-xl text-sm font-medium border-l-2 sm:border-l-0 sm:border-b-0 border-indigo-500 text-indigo-600 dark:text-indigo-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition" aria-current="page">Profile</button>
            <button type="button" data-settings-tab="account" class="settings-tab text-left px-4 py-2.5 rounded-xl text-sm font-medium border-l-2 sm:border-l-0 sm:border-b-0 border-transparent text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white transition">Account</button>
            <button type="button" data-settings-tab="security" class="settings-tab text-left px-4 py-2.5 rounded-xl text-sm font-medium border-l-2 sm:border-l-0 sm:border-b-0 border-transparent text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white transition">Security</button>
            <button type="button" data-settings-tab="preferences" class="settings-tab text-left px-4 py-2.5 rounded-xl text-sm font-medium border-l-2 sm:border-l-0 sm:border-b-0 border-transparent text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white transition">Preferences</button>
            <button type="button" data-settings-tab="notifications" class="settings-tab text-left px-4 py-2.5 rounded-xl text-sm font-medium border-l-2 sm:border-l-0 sm:border-b-0 border-transparent text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white transition">Notifications</button>
        </nav>

        <div class="flex-1 space-y-6">
            <div data-settings-panel="profile" class="space-y-6">
                <x-card title="Profile">
                    <form id="form-profile" action="{{ route('dashboard.settings.profile') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="profile-name" value="Name" />
                            <x-text-input id="profile-name" name="name" class="mt-1.5" :value="$user->name" />
                            <div data-field-error="name"></div>
                        </div>
                        <div>
                            <x-input-label for="profile-avatar" value="Avatar" />
                            <input type="file" id="profile-avatar" name="avatar" accept="image/*" class="mt-1.5 block w-full text-sm text-zinc-500 file:mr-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 dark:file:bg-indigo-950/50 dark:file:text-indigo-300" />
                            <div data-field-error="avatar"></div>
                        </div>
                        <x-primary-button type="submit" id="btn-profile">Save</x-primary-button>
                    </form>
                </x-card>
            </div>

            <div data-settings-panel="account" class="hidden space-y-6">
                <x-card title="Email">
                    <form id="form-email" action="{{ route('dashboard.settings.email') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="account-email" value="Email" />
                            <x-text-input id="account-email" name="email" type="email" class="mt-1.5" :value="$user->email" />
                            <div data-field-error="email"></div>
                        </div>
                        <x-primary-button type="submit" id="btn-email">Update email</x-primary-button>
                    </form>
                </x-card>
                <x-card title="Password">
                    <form id="form-password" action="{{ route('dashboard.settings.password') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="password-current" value="Current password" />
                            <x-text-input id="password-current" name="current_password" type="password" class="mt-1.5" />
                            <div data-field-error="current_password"></div>
                        </div>
                        <div>
                            <x-input-label for="password-new" value="New password" />
                            <x-text-input id="password-new" name="password" type="password" class="mt-1.5" />
                            <div data-field-error="password"></div>
                        </div>
                        <div>
                            <x-input-label for="password-confirm" value="Confirm password" />
                            <x-text-input id="password-confirm" name="password_confirmation" type="password" class="mt-1.5" />
                        </div>
                        <x-primary-button type="submit" id="btn-password">Update password</x-primary-button>
                    </form>
                </x-card>
            </div>

            <div data-settings-panel="security" class="hidden space-y-6">
                <x-card title="Two-factor authentication">
                    @if($user->two_factor_confirmed_at)
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">Two-factor authentication is enabled.</p>
                        <form method="POST" action="{{ route('two-factor.disable') }}" class="inline" onsubmit="return confirm('Are you sure you want to disable 2FA?');">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" variant="danger">Disable 2FA</x-button>
                        </form>
                        <a href="{{ route('dashboard.settings.2fa.recovery-codes') }}" class="ml-2 inline-flex items-center rounded-2xl border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">View recovery codes</a>
                        <form method="POST" action="{{ route('two-factor.regenerate-recovery-codes') }}" class="inline ml-2">
                            @csrf
                            <x-button type="submit" variant="secondary">Regenerate codes</x-button>
                        </form>
                    @else
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">Add an extra layer of security using an authenticator app.</p>
                        <a href="{{ route('dashboard.settings.2fa.setup') }}" class="inline-flex items-center rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Enable 2FA</a>
                    @endif
                </x-card>
                <x-card title="Logout other sessions">
                    <form id="form-logout-sessions" action="{{ route('dashboard.settings.security.logout-other-sessions') }}" method="POST" class="space-y-4">
                        @csrf
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Enter your password to log out of all other devices.</p>
                        <div>
                            <x-input-label for="logout-password" value="Password" />
                            <x-text-input id="logout-password" name="password" type="password" class="mt-1.5" />
                            <div data-field-error="password"></div>
                        </div>
                        <x-button type="submit" id="btn-logout-sessions" variant="secondary">Logout other sessions</x-button>
                    </form>
                </x-card>
            </div>

            <div data-settings-panel="preferences" class="hidden space-y-6">
                <x-card title="Workspace preferences">
                    <form id="form-preferences" action="{{ route('dashboard.settings.preferences') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="pref-timezone" value="Timezone" />
                            <x-ui.select id="pref-timezone" name="timezone" class="mt-1.5">
                                <option value="">Use default</option>
                                <option value="UTC" {{ ($user->preferences['timezone'] ?? '') === 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York" {{ ($user->preferences['timezone'] ?? '') === 'America/New_York' ? 'selected' : '' }}>Eastern</option>
                                <option value="America/Chicago" {{ ($user->preferences['timezone'] ?? '') === 'America/Chicago' ? 'selected' : '' }}>Central</option>
                                <option value="America/Denver" {{ ($user->preferences['timezone'] ?? '') === 'America/Denver' ? 'selected' : '' }}>Mountain</option>
                                <option value="America/Los_Angeles" {{ ($user->preferences['timezone'] ?? '') === 'America/Los_Angeles' ? 'selected' : '' }}>Pacific</option>
                                <option value="Europe/London" {{ ($user->preferences['timezone'] ?? '') === 'Europe/London' ? 'selected' : '' }}>London</option>
                            </x-ui.select>
                        </div>
                        <div>
                            <x-input-label for="pref-language" value="Language" />
                            <x-ui.select id="pref-language" name="language" class="mt-1.5">
                                <option value="">Use default</option>
                                <option value="en" {{ ($user->preferences['language'] ?? '') === 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ ($user->preferences['language'] ?? '') === 'es' ? 'selected' : '' }}>Spanish</option>
                                <option value="fr" {{ ($user->preferences['language'] ?? '') === 'fr' ? 'selected' : '' }}>French</option>
                            </x-ui.select>
                        </div>
                        <div>
                            <x-input-label for="pref-theme" value="Theme" />
                            <x-ui.select id="pref-theme" name="theme" class="mt-1.5">
                                <option value="system" {{ ($user->preferences['theme'] ?? 'system') === 'system' ? 'selected' : '' }}>System</option>
                                <option value="light" {{ ($user->preferences['theme'] ?? '') === 'light' ? 'selected' : '' }}>Light</option>
                                <option value="dark" {{ ($user->preferences['theme'] ?? '') === 'dark' ? 'selected' : '' }}>Dark</option>
                            </x-ui.select>
                        </div>
                        <x-primary-button type="submit" id="btn-preferences">Save preferences</x-primary-button>
                    </form>
                </x-card>
            </div>

            <div data-settings-panel="notifications" class="hidden space-y-6">
                <x-card title="Email notifications">
                    <form id="form-notifications" action="{{ route('dashboard.settings.notifications') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-zinc-900 dark:text-white">Post notifications</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">Get notified when posts are published</p>
                            </div>
                            <x-ui.toggle name="notify_posts" :checked="($user->preferences['notify_posts'] ?? true)" label="" />
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-zinc-900 dark:text-white">Weekly digest</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">Receive a weekly summary email</p>
                            </div>
                            <x-ui.toggle name="notify_weekly" :checked="($user->preferences['notify_weekly'] ?? false)" label="" />
                        </div>
                        <x-primary-button type="submit" id="btn-notifications">Save</x-primary-button>
                    </form>
                </x-card>
            </div>
        </div>
    </div>
</div>
@endsection
