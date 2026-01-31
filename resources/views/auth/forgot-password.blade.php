<x-guest-layout>
    <p class="mb-5 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just enter your email and we will send you a link to choose a new one.') }}
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between pt-2">
            <a class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white" href="{{ route('login') }}">
                {{ __('Back to login') }}
            </a>
            <x-primary-button class="w-full sm:w-auto">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
