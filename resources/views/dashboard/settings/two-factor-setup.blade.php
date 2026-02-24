@extends('layouts.app')

@push('vite')
    @vite(['resources/js/settings.js'])
@endpush

@section('content')
<div class="max-w-md mx-auto">
    <x-ui.page-header title="Enable Two-Factor Authentication" description="Scan the QR code with your authenticator app" />

    <x-card>
        <div id="2fa-setup-content" class="space-y-4">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Loading...</p>
        </div>
        <form id="form-2fa-confirm" action="{{ route('two-factor.confirm') }}" method="POST" class="hidden mt-6 space-y-4">
            @csrf
            <div>
                <x-input-label for="code" value="Authentication code" />
                <x-text-input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" placeholder="000000" maxlength="6" class="mt-1.5 font-mono text-lg tracking-widest" />
                <x-input-error :messages="$errors->get('code')" class="mt-1.5" />
            </div>
            <x-primary-button type="submit">Confirm and enable</x-primary-button>
        </form>
    </x-card>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const content = document.getElementById('2fa-setup-content');
    const form = document.getElementById('form-2fa-confirm');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    async function init() {
        let res = await fetch('/user/two-factor-qr-code', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        });
        let data = await res.json();
        if (!data.svg) {
            await fetch('/user/two-factor-authentication', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
                body: JSON.stringify({ _token: csrf }),
            });
            res = await fetch('/user/two-factor-qr-code', {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });
            data = await res.json();
        }
        if (data.svg) {
            content.innerHTML = `
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Scan this QR code with your authenticator app (Google Authenticator, Authy, etc.).</p>
                <div class="flex justify-center p-4 bg-white rounded-xl">${data.svg}</div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Then enter the 6-digit code from your app below.</p>
            `;
            form.classList.remove('hidden');
        } else {
            content.innerHTML = '<p class="text-sm text-red-600">Unable to generate QR code. Please try again.</p>';
        }
    }
    init();
});
</script>
@endsection
