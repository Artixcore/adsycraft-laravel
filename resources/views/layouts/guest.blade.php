<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Laravel') }} – {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen bg-gradient-to-br from-slate-50 via-indigo-50/30 to-slate-100 dark:from-zinc-950 dark:via-indigo-950/30 dark:to-zinc-950 text-zinc-900 dark:text-zinc-100">
    <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-8 sm:py-12">
        <div class="absolute top-4 right-4">
            <button type="button" data-theme-toggle class="rounded-xl p-2.5 text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition" aria-label="Toggle theme">
                <span class="dark:hidden"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg></span>
                <span class="hidden dark:inline"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg></span>
            </button>
        </div>
        <a href="{{ url('/') }}" class="flex items-center gap-3 mb-6 sm:mb-8">
            <x-application-logo class="w-12 h-12 sm:w-14 sm:h-14" />
            <span class="text-xl sm:text-2xl font-semibold text-gray-800 dark:text-white">{{ config('app.name') }}</span>
        </a>

        <div class="w-full sm:max-w-md rounded-2xl bg-white/90 dark:bg-gray-800/95 shadow-xl shadow-gray-200/50 dark:shadow-none ring-1 ring-gray-200/50 dark:ring-gray-700/50 backdrop-blur-sm overflow-hidden px-6 py-8 sm:px-8 sm:py-10">
            {{ $slot }}
        </div>

        <p class="mt-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
            &copy; {{ date('Y') }} {{ config('app.name') }}
        </p>
    </div>
    <x-toast-container />
    @stack('scripts')
</body>
</html>
