@php
    $navItems = [
        ['label' => 'Dashboard', 'href' => route('dashboard'), 'route' => 'dashboard', 'icon' => 'dashboard'],
        ['label' => 'Businesses', 'href' => route('dashboard'), 'route' => 'dashboard', 'icon' => 'building'],
        ['label' => 'Content Calendar', 'href' => route('dashboard.calendar'), 'route' => 'dashboard.calendar', 'icon' => 'calendar'],
        ['label' => 'Connectors', 'href' => route('dashboard.connectors'), 'route' => 'dashboard.connectors', 'icon' => 'link'],
        ['label' => 'Growth Blueprint', 'href' => route('dashboard.growth-blueprint'), 'route' => 'dashboard.growth-blueprint', 'icon' => 'blueprint'],
        ['label' => 'Ad Library', 'href' => route('dashboard.ad-library'), 'route' => 'dashboard.ad-library', 'icon' => 'library'],
        ['label' => 'Ads', 'href' => route('dashboard.ads'), 'route' => 'dashboard.ads', 'icon' => 'megaphone'],
        ['label' => 'Reports', 'href' => '#', 'route' => null, 'icon' => 'reports'],
        ['label' => 'Settings', 'href' => route('dashboard.settings'), 'route' => 'dashboard.settings', 'icon' => 'cog'],
    ];
@endphp

<aside
    id="app-sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 flex-shrink-0 border-r border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 lg:static lg:translate-x-0 transition-transform duration-200 ease-in-out -translate-x-full"
    aria-label="Main navigation"
>
    <div class="flex h-full flex-col">
        <div class="flex h-14 items-center justify-between px-4 border-b border-zinc-200 dark:border-zinc-800 lg:border-b-0 lg:px-6 lg:py-6">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <span class="text-lg font-semibold text-zinc-900 dark:text-white">AdsyCraft</span>
            </a>
            <button
                type="button"
                id="sidebar-close"
                class="rounded-lg p-2 text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-700 dark:hover:text-zinc-300 lg:hidden focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900"
                aria-label="Close sidebar"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto px-3 py-4 lg:px-4" aria-label="Dashboard sections">
            <ul class="space-y-0.5">
                @foreach($navItems as $item)
                    @php
                        $isActive = $item['route'] && ($item['route'] === 'dashboard' ? request()->route()->getName() === 'dashboard' : request()->routeIs($item['route']));
                        $baseClass = 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900';
                        $activeClass = 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300';
                        $inactiveClass = 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white';
                    @endphp
                    <li>
                        <a href="{{ $item['href'] }}" class="{{ $baseClass }} {{ $isActive ? $activeClass : $inactiveClass }}">
                            @if($item['icon'] === 'dashboard')
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            @elseif($item['icon'] === 'building')
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            @elseif($item['icon'] === 'calendar')
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @elseif($item['icon'] === 'link')
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            @elseif($item['icon'] === 'library')
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @elseif($item['icon'] === 'megaphone')
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            @elseif($item['icon'] === 'chart' || $item['icon'] === 'blueprint')
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @elseif($item['icon'] === 'reports')
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            @else
                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            @endif
                            <span>{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
