<header class="sticky top-0 z-10 flex items-center justify-between h-14 px-4 sm:px-6 border-b border-zinc-200 dark:border-[#27272A] bg-white/95 dark:bg-[#0C0C0C]/95 backdrop-blur">
    <div class="flex items-center gap-6">
        <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-zinc-900 dark:text-white">AdsyCraft</a>
        <nav class="hidden sm:flex items-center gap-1" aria-label="Main">
            <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') && !request()->routeIs('dashboard.connectors') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-[#1a1a1a] hover:text-zinc-900 dark:hover:text-white' }}">Mission Control</a>
            <a href="{{ route('dashboard.connectors') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard.connectors') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-[#1a1a1a] hover:text-zinc-900 dark:hover:text-white' }}">Connectors</a>
            <a href="{{ route('dashboard.ad-library') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard.ad-library') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-[#1a1a1a] hover:text-zinc-900 dark:hover:text-white' }}">Ad Library</a>
            @if(auth()->user()?->role?->value === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#1c1c1a] hover:text-gray-900 dark:hover:text-white">Admin</a>
            @endif
        </nav>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-[120px] sm:max-w-[180px]" title="{{ auth()->user()->email }}">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <x-button type="submit" variant="ghost" class="!py-1.5 !px-2">Sign Out</x-button>
        </form>
    </div>
</header>
