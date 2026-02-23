@extends('layouts.app')

@push('vite')
    @vite(['resources/js/dashboard.js'])
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <x-ui.page-header title="Mission Control" description="Your command center for AI-powered social growth" />

    {{-- Stat cards row --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-ui.stat-card label="Posts Today" value="0" trend="vs yesterday">
            <x-slot:icon><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg></x-slot:icon>
        </x-ui.stat-card>
        <x-ui.stat-card label="Autopilot" value="—">
            <x-slot:icon><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></x-slot:icon>
        </x-ui.stat-card>
        <x-ui.stat-card label="Connections" value="0">
            <x-slot:icon><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg></x-slot:icon>
        </x-ui.stat-card>
        <x-ui.stat-card label="AI Status" value="Ready">
            <x-slot:icon><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg></x-slot:icon>
        </x-ui.stat-card>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Today's Autopilot panel --}}
        <x-card title="Today's Autopilot">
            <div class="space-y-3">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Scheduled posts for today</p>
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                            <tr>
                                <th class="text-left px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Time</th>
                                <th class="text-left px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Platform</th>
                                <th class="text-left px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Status</th>
                            </tr>
                        </thead>
                        <tbody id="autopilot-posts-tbody" class="divide-y divide-zinc-200 dark:divide-zinc-800">
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">No posts scheduled</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card>

        {{-- Activity log panel --}}
        <x-card title="Activity Log">
            <div class="space-y-0 divide-y divide-zinc-200 dark:divide-zinc-800">
                <x-ui.activity-item title="Welcome to Mission Control" time="Just now">
                    <x-slot:icon><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></x-slot:icon>
                </x-ui.activity-item>
                <x-ui.activity-item title="Connect Meta to get started" time="—" meta="Connectors">
                    <x-slot:icon><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg></x-slot:icon>
                </x-ui.activity-item>
            </div>
        </x-card>
    </div>

    {{-- Trends panel --}}
    <x-card title="Trends">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Engagement</p>
                <p class="mt-1 text-xl font-bold text-zinc-900 dark:text-white">—</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-500">Connect accounts to see data</p>
            </div>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Reach</p>
                <p class="mt-1 text-xl font-bold text-zinc-900 dark:text-white">—</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-500">Connect accounts to see data</p>
            </div>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Posts</p>
                <p class="mt-1 text-xl font-bold text-zinc-900 dark:text-white">0</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-500">This week</p>
            </div>
        </div>
    </x-card>

    {{-- Quick actions panel --}}
    <x-card title="Quick Actions">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('dashboard.connectors') }}" class="inline-flex items-center justify-center rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-4 py-2.5 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900">
                Connect Meta
            </a>
            <button type="button" id="btn-quick-generate" class="inline-flex items-center justify-center rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-4 py-2.5 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900">
                Generate content
            </button>
            <button type="button" id="btn-quick-autopilot" class="inline-flex items-center justify-center rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-4 py-2.5 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900">
                Toggle autopilot
            </button>
        </div>
    </x-card>

    {{-- Your Businesses --}}
    <x-card title="Your Businesses">
        <div id="business-list" class="space-y-2">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Loading…</p>
        </div>
    </x-card>

    {{-- Create new business --}}
    <x-card title="Create new business">
        <form id="create-business-form" class="space-y-4 max-w-md">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Name *</label>
                    <input type="text" name="name" required class="w-full rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-offset-zinc-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Niche</label>
                    <input type="text" name="niche" class="w-full rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-offset-zinc-900">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Website URL</label>
                <input type="url" name="website_url" class="w-full rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-offset-zinc-900">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Tone</label>
                    <input type="text" name="tone" class="w-full rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-offset-zinc-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Language</label>
                    <input type="text" name="language" placeholder="e.g. en" class="w-full rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-offset-zinc-900">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Posts per day</label>
                    <input type="number" name="posts_per_day" min="1" max="20" value="1" class="w-full rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-offset-zinc-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Timezone *</label>
                    <input type="text" name="timezone" required placeholder="e.g. America/New_York" value="UTC" class="w-full rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-offset-zinc-900">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="autopilot_enabled" id="autopilot_enabled" value="1" class="rounded border-zinc-300 dark:border-zinc-600 text-indigo-600 focus:ring-indigo-500">
                <label for="autopilot_enabled" class="text-sm text-zinc-700 dark:text-zinc-300">Autopilot enabled</label>
            </div>
            <x-button type="submit" variant="primary">Create business</x-button>
        </form>
        <p id="create-message" class="mt-2 text-sm hidden"></p>
    </x-card>

    {{-- Selected business section (hidden by default) --}}
    <section id="selected-business-section" class="hidden">
        <x-card>
            <div class="border-b border-zinc-200 dark:border-zinc-800 -mx-6 -mt-6 px-6 py-4 mb-4 bg-zinc-50/50 dark:bg-zinc-800/50">
                <h3 class="text-base font-semibold text-zinc-900 dark:text-white">Selected: <span id="selected-business-name"></span></h3>
            </div>
            <div class="flex flex-wrap gap-2 mb-4">
                <x-button type="button" id="btn-generate-today" variant="secondary">Generate today</x-button>
                <x-button type="button" id="btn-toggle-autopilot" variant="secondary">Toggle autopilot</x-button>
            </div>
            <p id="selected-message" class="text-sm text-zinc-500 dark:text-zinc-400 mb-4"></p>

            <h3 class="text-sm font-semibold text-zinc-900 dark:text-white mb-2">Posts</h3>
            <div id="posts-container" class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-800">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                        <tr>
                            <th class="text-left px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">ID</th>
                            <th class="text-left px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Status</th>
                            <th class="text-left px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Scheduled</th>
                            <th class="text-left px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Caption</th>
                        </tr>
                    </thead>
                    <tbody id="posts-tbody" class="divide-y divide-zinc-200 dark:divide-zinc-800">
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
