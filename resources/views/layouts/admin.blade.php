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
        <div id="admin-sidebar-overlay" class="fixed inset-0 z-30 bg-zinc-900/50 backdrop-blur-sm lg:hidden hidden" aria-hidden="true"></div>
        <div class="flex-1 flex flex-col min-w-0">
            @include('partials.admin-topbar')
            <main class="flex-1 p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>
    <x-toast-container />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('admin-sidebar-overlay');
            const toggleBtn = document.getElementById('admin-sidebar-toggle');
            const closeBtn = document.getElementById('admin-sidebar-close');

            function openSidebar() {
                sidebar?.classList.remove('-translate-x-full');
                sidebar?.classList.remove('hidden');
                overlay?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            function closeSidebar() {
                if (window.innerWidth < 1024) {
                    sidebar?.classList.add('-translate-x-full');
                    overlay?.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            }
            toggleBtn?.addEventListener('click', openSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeSidebar();
            });
        });
    </script>
</body>
</html>
