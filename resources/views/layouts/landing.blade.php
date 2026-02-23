<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'AdsyCraft' }} – {{ config('app.name', 'AdsyCraft') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('vite')
</head>
<body class="bg-[#FAFAF9] dark:bg-[#0C0C0C] text-[#18181B] dark:text-[#FAFAFA] min-h-screen font-sans antialiased">
    <div class="flex flex-col min-h-screen">
        {{-- Header --}}
        <header class="sticky top-0 z-50 flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8 border-b border-zinc-200 dark:border-[#27272A] bg-white/90 dark:bg-[#0C0C0C]/90 backdrop-blur-sm">
            <a href="{{ route('home') }}" class="text-lg font-semibold text-zinc-900 dark:text-white">AdsyCraft</a>
            <nav class="hidden md:flex items-center gap-1" aria-label="Main">
                <a href="{{ route('features') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-[#1a1a1a] transition">Features</a>
                <a href="{{ route('pricing') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-[#1a1a1a] transition">Pricing</a>
                <a href="{{ route('about') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-[#1a1a1a] transition">About</a>
                <a href="{{ route('faq') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-[#1a1a1a] transition">FAQ</a>
            </nav>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">Mission Control</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">Sign Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">Sign In</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-[#0C0C0C] transition">Launch Mission Control</a>
                @endauth
            </div>
        </header>

        <main class="flex-1">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="border-t border-zinc-200 dark:border-[#27272A] bg-white dark:bg-[#161616] py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <a href="{{ route('features') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">Features</a>
                        <a href="{{ route('pricing') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">Pricing</a>
                        <a href="{{ route('about') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">About</a>
                        <a href="{{ route('faq') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">FAQ</a>
                        <a href="{{ route('login') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">Login</a>
                    </div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-500">
                        Developed by <a href="https://artixcore.com" target="_blank" rel="noopener" class="text-zinc-700 dark:text-zinc-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">Artixcore</a> — <a href="https://artixcore.com" target="_blank" rel="noopener" class="text-zinc-700 dark:text-zinc-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">artixcore.com</a>
                    </p>
                </div>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
