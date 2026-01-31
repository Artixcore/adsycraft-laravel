<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} – {{ config('app.name', 'Adsycraft') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    @stack('vite')
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen font-sans antialiased">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-56 flex-shrink-0 border-r border-gray-200 dark:border-[#252523] bg-white dark:bg-[#111110] hidden lg:block">
            <div class="sticky top-0 flex flex-col h-screen py-6 pl-4 pr-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 mb-8">
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                </a>
                <nav class="space-y-0.5">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') && !request()->routeIs('dashboard.connectors') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#1c1c1a] hover:text-gray-900 dark:hover:text-white' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') && !request()->routeIs('dashboard.connectors') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#1c1c1a] hover:text-gray-900 dark:hover:text-white' }}">
                        Businesses
                    </a>
                    <a href="{{ route('dashboard.connectors') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard.connectors') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#1c1c1a] hover:text-gray-900 dark:hover:text-white' }}">
                        Connectors
                    </a>
                </nav>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top navbar -->
            <header class="sticky top-0 z-10 flex items-center justify-between h-14 px-4 sm:px-6 border-b border-gray-200 dark:border-[#252523] bg-white/95 dark:bg-[#111110]/95 backdrop-blur">
                <div class="flex items-center gap-4 lg:hidden">
                    <span class="text-base font-semibold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                </div>
                <div class="flex items-center gap-3 ml-auto">
                    <span class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-[120px] sm:max-w-[180px]" title="{{ auth()->user()->email }}">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
