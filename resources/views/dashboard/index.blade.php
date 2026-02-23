@extends('layouts.app')

@push('vite')
    @vite(['resources/js/dashboard.js'])
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    {{-- Status strip --}}
    <section class="flex flex-wrap items-center gap-4 rounded-xl border border-zinc-200 dark:border-[#27272A] bg-white dark:bg-[#161616] px-4 py-3">
        <div class="flex items-center gap-2">
            <span class="inline-flex h-2 w-2 rounded-full bg-amber-500" title="Connection status"></span>
            <span class="text-sm text-zinc-600 dark:text-zinc-400">Meta</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex h-2 w-2 rounded-full bg-amber-500" title="AI status"></span>
            <span class="text-sm text-zinc-600 dark:text-zinc-400">AI</span>
        </div>
        <div class="text-sm text-zinc-500 dark:text-zinc-500">|</div>
        <span class="text-sm text-zinc-600 dark:text-zinc-400">Autopilot: <span id="status-autopilot" class="font-medium text-zinc-900 dark:text-white">—</span></span>
    </section>

    {{-- Command cards --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-card class="!p-4 hover:border-indigo-200 dark:hover:border-indigo-900/50 transition">
            <a href="#business-list" class="flex items-center gap-3 group">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-950/50 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-900/50 transition">
                    <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-zinc-900 dark:text-white">Your Businesses</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Manage below</p>
                </div>
            </a>
        </x-card>
        <x-card class="!p-4 hover:border-indigo-200 dark:hover:border-indigo-900/50 transition">
            <a href="{{ route('dashboard.connectors') }}" class="flex items-center gap-3 group">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-zinc-100 dark:bg-[#1a1a1a] group-hover:bg-indigo-100 dark:group-hover:bg-indigo-950/50 transition">
                    <svg class="h-5 w-5 text-zinc-600 dark:text-zinc-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-zinc-900 dark:text-white group-hover:text-indigo-700 dark:group-hover:text-indigo-300">Connect Meta</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Link Facebook & Instagram</p>
                </div>
            </a>
        </x-card>
        <x-card class="!p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-zinc-100 dark:bg-[#1a1a1a]">
                    <svg class="h-5 w-5 text-zinc-600 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-zinc-900 dark:text-white">Recent Activity</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">See calendar below</p>
                </div>
            </div>
        </x-card>
        <x-card class="!p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-zinc-100 dark:bg-[#1a1a1a]">
                    <svg class="h-5 w-5 text-zinc-600 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-zinc-900 dark:text-white">Quick Actions</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Generate, Toggle autopilot</p>
                </div>
            </div>
        </x-card>
    </section>

    <x-card title="Your Businesses">
        <div id="business-list" class="space-y-2">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Loading…</p>
        </div>
    </x-card>

    <x-card title="Create new business">
        <form id="create-business-form" class="space-y-4 max-w-md">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Name *</label>
                    <input type="text" name="name" required class="w-full rounded-lg border border-zinc-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Niche</label>
                    <input type="text" name="niche" class="w-full rounded-lg border border-zinc-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Website URL</label>
                <input type="url" name="website_url" class="w-full rounded-lg border border-zinc-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Tone</label>
                    <input type="text" name="tone" class="w-full rounded-lg border border-zinc-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Language</label>
                    <input type="text" name="language" placeholder="e.g. en" class="w-full rounded-lg border border-zinc-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Posts per day</label>
                    <input type="number" name="posts_per_day" min="1" max="20" value="1" class="w-full rounded-lg border border-zinc-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Timezone *</label>
                    <input type="text" name="timezone" required placeholder="e.g. America/New_York" value="UTC" class="w-full rounded-lg border border-zinc-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="autopilot_enabled" id="autopilot_enabled" value="1" class="rounded border-zinc-300 dark:border-[#3E3E3A] text-indigo-600 focus:ring-indigo-500">
                <label for="autopilot_enabled" class="text-sm text-zinc-700 dark:text-zinc-300">Autopilot enabled</label>
            </div>
            <x-button type="submit" variant="primary">Create business</x-button>
        </form>
        <p id="create-message" class="mt-2 text-sm hidden"></p>
    </x-card>

    <section id="selected-business-section" class="hidden">
        <x-card>
            <div class="border-b border-zinc-200 dark:border-[#27272A] -mx-6 -mt-6 px-6 py-4 mb-4 bg-zinc-50/50 dark:bg-[#161615]/50">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-white">Selected: <span id="selected-business-name"></span></h3>
            </div>
            <div class="flex flex-wrap gap-2 mb-4">
                <x-button type="button" id="btn-generate-today" variant="secondary">Generate today</x-button>
                <x-button type="button" id="btn-toggle-autopilot" variant="secondary">Toggle autopilot</x-button>
            </div>
            <p id="selected-message" class="text-sm text-zinc-500 dark:text-zinc-400 mb-4"></p>

            <h3 class="text-sm font-semibold text-zinc-900 dark:text-white mb-2">Posts</h3>
            <div id="posts-container" class="overflow-hidden rounded-lg border border-zinc-200 dark:border-[#252523]">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-[#252523] text-sm">
                    <thead class="bg-zinc-50 dark:bg-[#161615]">
                        <tr>
                            <th class="text-left px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">ID</th>
                            <th class="text-left px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Status</th>
                            <th class="text-left px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Scheduled</th>
                            <th class="text-left px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Caption</th>
                        </tr>
                    </thead>
                    <tbody id="posts-tbody" class="divide-y divide-zinc-200 dark:divide-[#252523]">
                        <tr><td colspan="4" class="px-4 py-3 text-zinc-500 dark:text-zinc-400">No posts</td></tr>
                    </tbody>
                </table>
            </div>

            <h3 class="text-sm font-semibold text-zinc-900 dark:text-white mt-6 mb-2">Calendar feed</h3>
            <div id="calendar-container">
                <ul id="calendar-list" class="list-disc list-inside text-sm space-y-1 text-zinc-600 dark:text-zinc-400">
                    <li>No events</li>
                </ul>
            </div>
        </x-card>
    </section>
</div>
@endsection
