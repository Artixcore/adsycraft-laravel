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
<body class="bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 min-h-screen font-sans antialiased">
    <div class="flex flex-col min-h-screen">
        <header class="sticky top-0 z-50 flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8 border-b border-zinc-200 dark:border-zinc-800 bg-white/95 dark:bg-zinc-950/95 backdrop-blur-sm">
            <a href="{{ route('home') }}" class="text-lg font-semibold text-zinc-900 dark:text-white">AdsyCraft</a>
            <nav class="hidden md:flex items-center gap-1" aria-label="Main">
                <a href="{{ route('features') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">Features</a>
                <a href="{{ route('pricing') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">Pricing</a>
                <a href="{{ route('about') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">About</a>
                <a href="{{ route('faq') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">FAQ</a>
            </nav>
            <div class="flex items-center gap-2">
                <button type="button" data-theme-toggle class="rounded-xl p-2 text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-700 dark:hover:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-950 transition" aria-label="Toggle theme">
                    <span class="dark:hidden"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg></span>
                    <span class="hidden dark:inline"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg></span>
                </button>
                @auth
                    <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-950 transition">Mission Control</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline hidden sm:block">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">Sign Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">Sign In</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-950 transition shadow-sm">Launch Mission Control</a>
                @endauth
                <button type="button" id="mobile-menu-toggle" class="md:hidden rounded-xl p-2 text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800" aria-label="Open menu">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </header>

        <div id="mobile-menu" class="hidden md:hidden border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 px-4 py-4">
            <nav class="flex flex-col gap-1" aria-label="Mobile navigation">
                <a href="{{ route('features') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800">Features</a>
                <a href="{{ route('pricing') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800">Pricing</a>
                <a href="{{ route('about') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800">About</a>
                <a href="{{ route('faq') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800">FAQ</a>
                @guest
                    <a href="{{ route('login') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-zinc-600 dark:text-zinc-400">Sign In</a>
                    <a href="{{ route('register') }}" class="px-3 py-2 rounded-xl text-sm font-medium text-indigo-600 dark:text-indigo-400">Launch Mission Control</a>
                @endguest
            </nav>
        </div>

        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 sm:gap-6">
                        <a href="{{ route('features') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">Features</a>
                        <a href="{{ route('pricing') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">Pricing</a>
                        <a href="{{ route('faq') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">FAQ</a>
                        <a href="{{ route('about') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">About</a>
                        <a href="{{ route('privacy') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">Privacy Policy</a>
                        <a href="{{ route('terms') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">Terms of Service</a>
                        <a href="{{ route('login') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">Login</a>
                        <a href="{{ route('register') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">Register</a>
                    </div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-500 text-center sm:text-right">
                        Developed by <a href="https://artixcore.com" target="_blank" rel="noopener" class="text-zinc-700 dark:text-zinc-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">Artixcore</a> – <a href="https://artixcore.com" target="_blank" rel="noopener" class="text-zinc-700 dark:text-zinc-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">artixcore.com</a>
                    </p>
                </div>
            </div>
        </footer>
    </div>
    <script>
        document.getElementById('mobile-menu-toggle')?.addEventListener('click', function() {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
