<x-guest-layout>
    <div class="space-y-6">
        <p class="text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>

        @if (session('status') === 'verification-link-sent')
            <div class="rounded-xl border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-950/40 px-4 py-3 text-sm text-green-800 dark:text-green-200">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <form method="POST" action="{{ route('verification.send') }}" class="inline">
                @csrf
                <x-primary-button type="submit" class="w-full sm:w-auto">
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </form>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white underline">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
