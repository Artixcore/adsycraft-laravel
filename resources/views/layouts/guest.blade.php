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
<body class="font-sans text-gray-900 dark:text-gray-100 antialiased min-h-screen bg-gradient-to-br from-slate-50 via-indigo-50/30 to-slate-100 dark:from-gray-900 dark:via-indigo-950/20 dark:to-gray-900">
    <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-12 sm:py-0">
        <a href="/" class="flex items-center gap-3 mb-6 sm:mb-8">
            <x-application-logo class="w-12 h-12 sm:w-14 sm:h-14" />
            <span class="text-xl sm:text-2xl font-semibold text-gray-800 dark:text-white">{{ config('app.name') }}</span>
        </a>

        <div class="w-full sm:max-w-md rounded-2xl bg-white/90 dark:bg-gray-800/95 shadow-xl shadow-gray-200/50 dark:shadow-none ring-1 ring-gray-200/50 dark:ring-gray-700/50 backdrop-blur-sm overflow-hidden px-6 py-8 sm:px-8 sm:py-10">
            {{ $slot }}
        </div>

        <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            &copy; {{ date('Y') }} {{ config('app.name') }}
        </p>
    </div>
</body>
</html>
