<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} – {{ config('app.name', 'Adsycraft') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('vite')
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen font-sans antialiased">
    <div class="flex min-h-screen">
        @include('partials.admin-sidebar')
        <div class="flex-1 flex flex-col min-w-0">
            @include('partials.admin-topbar')
            <main class="flex-1 p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>
    <x-toast-container />
</body>
</html>
