<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} – {{ config('app.name', 'AdsyCraft') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('vite')
</head>
<body class="bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 min-h-screen font-sans antialiased">
    <div class="flex min-h-screen">
        <x-sidebar />

        <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-zinc-900/50 backdrop-blur-sm lg:hidden hidden" aria-hidden="true"></div>

        <div class="flex flex-1 flex-col min-w-0">
            <x-navbar />

            <main class="flex-1 p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>
    <x-toast-container />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('app-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggleBtn = document.getElementById('sidebar-toggle');
            const closeBtn = document.getElementById('sidebar-close');
            const userMenuBtn = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');

            function openSidebar() {
                sidebar?.classList.remove('-translate-x-full');
                overlay?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            function closeSidebar() {
                sidebar?.classList.add('-translate-x-full');
                overlay?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            toggleBtn?.addEventListener('click', openSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);

            userMenuBtn?.addEventListener('click', function() {
                userMenu?.classList.toggle('hidden');
            });
            document.addEventListener('click', function(e) {
                if (userMenu && !userMenu.classList.contains('hidden') && !userMenuBtn?.contains(e.target) && !userMenu.contains(e.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
