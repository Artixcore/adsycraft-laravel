<header class="sticky top-0 z-10 flex items-center justify-between h-14 px-4 sm:px-6 border-b border-gray-200 dark:border-[#252523] bg-white/95 dark:bg-[#111110]/95 backdrop-blur">
    <div class="flex items-center gap-4">
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 lg:hidden">{{ config('app.name') }} Admin</span>
        <div class="hidden sm:flex items-center gap-2 flex-wrap">
            <x-button href="{{ route('admin.automations.create') }}" variant="primary" class="!py-2 !text-xs">Create Automation</x-button>
            <x-button href="{{ route('dashboard.connectors') }}" variant="secondary" class="!py-2 !text-xs">Connect Account</x-button>
            <x-button href="{{ route('admin.logs.index') }}" variant="secondary" class="!py-2 !text-xs">View Logs</x-button>
            <x-button href="{{ route('admin.users.index') }}" variant="secondary" class="!py-2 !text-xs">Manage Users</x-button>
        </div>
    </div>
    <div class="flex items-center gap-3 ml-auto">
        <span class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-[120px] sm:max-w-[180px]" title="{{ auth()->user()->email }}">{{ auth()->user()->name }}</span>
        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">User dashboard</a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <x-button type="submit" variant="ghost" class="!py-1.5 !px-2">Logout</x-button>
        </form>
    </div>
</header>
