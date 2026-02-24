<aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 w-56 flex-shrink-0 border-r border-gray-200 dark:border-[#252523] bg-white dark:bg-[#111110] lg:static lg:translate-x-0 -translate-x-full transition-transform duration-200 ease-in-out flex flex-col" aria-label="Admin navigation">
    <div class="sticky top-0 flex flex-col h-screen py-6 pl-4 pr-3">
        <div class="flex items-center justify-between px-3 mb-8">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
            <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
        </a>
        <button type="button" id="admin-sidebar-close" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-[#1c1c1a] lg:hidden focus:outline-none focus:ring-2 focus:ring-indigo-500" aria-label="Close sidebar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        </div>
        <nav class="space-y-0.5 flex-1" aria-label="Admin sections">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') && !request()->routeIs('admin.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#1c1c1a] hover:text-gray-900 dark:hover:text-white' }}" title="Dashboard">Dashboard</a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#1c1c1a] hover:text-gray-900 dark:hover:text-white' }}" title="Users">Users</a>
            <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.roles.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#1c1c1a] hover:text-gray-900 dark:hover:text-white' }}" title="Roles">Roles</a>
            <a href="{{ route('admin.meta-accounts.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.meta-accounts.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#1c1c1a] hover:text-gray-900 dark:hover:text-white' }}" title="Meta accounts">Meta accounts</a>
            <a href="{{ route('admin.automations.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.automations.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#1c1c1a] hover:text-gray-900 dark:hover:text-white' }}" title="Automations">Automations</a>
            <a href="{{ route('admin.logs.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.logs.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#1c1c1a] hover:text-gray-900 dark:hover:text-white' }}" title="Logs">Logs</a>
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-[#1c1c1a] hover:text-gray-900 dark:hover:text-white' }}" title="Settings">Settings</a>
        </nav>
    </div>
</aside>
