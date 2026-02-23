@extends('layouts.app')

@push('vite')
    @vite(['resources/js/calendar.js'])
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <x-ui.page-header title="Content Calendar" description="Plan and manage your scheduled posts" />

    <x-card title="Select business">
        <div id="business-selector" class="space-y-2">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Loading…</p>
        </div>
    </x-card>

    <div id="calendar-content" class="hidden space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <button type="button" id="btn-prev-month" class="rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                    ← Prev
                </button>
                <h2 id="calendar-title" class="text-lg font-semibold text-zinc-900 dark:text-white min-w-[180px] text-center">—</h2>
                <button type="button" id="btn-next-month" class="rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                    Next →
                </button>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="btn-view-month" class="rounded-lg border border-indigo-300 dark:border-indigo-700 bg-indigo-50 dark:bg-indigo-950/50 px-3 py-2 text-sm font-medium text-indigo-700 dark:text-indigo-300">
                    Month
                </button>
                <button type="button" id="btn-view-week" class="rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                    Week
                </button>
            </div>
        </div>

        <div id="calendar-loading" class="flex justify-center py-12">
            <span class="inline-block animate-spin text-2xl text-zinc-400">⟳</span>
        </div>

        <div id="calendar-grid" class="hidden">
            <div class="grid grid-cols-7 gap-px bg-zinc-200 dark:bg-zinc-700 rounded-xl overflow-hidden">
                <div class="bg-zinc-100 dark:bg-zinc-800 px-2 py-2 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400">Sun</div>
                <div class="bg-zinc-100 dark:bg-zinc-800 px-2 py-2 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400">Mon</div>
                <div class="bg-zinc-100 dark:bg-zinc-800 px-2 py-2 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400">Tue</div>
                <div class="bg-zinc-100 dark:bg-zinc-800 px-2 py-2 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400">Wed</div>
                <div class="bg-zinc-100 dark:bg-zinc-800 px-2 py-2 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400">Thu</div>
                <div class="bg-zinc-100 dark:bg-zinc-800 px-2 py-2 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400">Fri</div>
                <div class="bg-zinc-100 dark:bg-zinc-800 px-2 py-2 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400">Sat</div>
                <div id="calendar-cells" class="col-span-7 grid grid-cols-7 auto-rows-fr min-h-[400px]">
                    {{-- Filled by JS --}}
                </div>
            </div>
        </div>

        <x-empty-state id="calendar-empty" title="No business selected" description="Select a business above to view the calendar" class="hidden" />
    </div>
</div>

{{-- Day Detail Side Panel --}}
<div id="day-panel-backdrop" class="fixed inset-0 z-40 bg-zinc-900/50 backdrop-blur-sm hidden" aria-hidden="true"></div>
<div id="day-panel" class="fixed inset-y-0 right-0 z-50 w-full max-w-lg bg-white dark:bg-zinc-900 shadow-xl flex flex-col transform translate-x-full transition-transform duration-300 ease-out">
    <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-200 dark:border-zinc-800">
        <h3 id="day-panel-title" class="text-lg font-semibold text-zinc-900 dark:text-white">—</h3>
        <button type="button" id="day-panel-close" class="rounded-lg p-2 text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-700 dark:hover:text-zinc-300" aria-label="Close">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div id="day-panel-content" class="flex-1 overflow-y-auto p-4 space-y-4">
        <div id="day-panel-loading" class="flex justify-center py-12">
            <span class="inline-block animate-spin text-2xl text-zinc-400">⟳</span>
        </div>
        <div id="day-panel-body" class="hidden space-y-4">
            {{-- Summary, engagement, posts --}}
        </div>
    </div>
</div>

{{-- Edit Post Modal --}}
<div id="edit-post-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
    <div data-modal-backdrop class="absolute inset-0 bg-zinc-900/50 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-xl p-6">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Edit post</h3>
            <form id="edit-post-form" class="space-y-4">
                <input type="hidden" id="edit-post-id" name="post_id">
                <div>
                    <label for="edit-post-caption" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Caption</label>
                    <textarea id="edit-post-caption" name="caption" rows="4" class="w-full rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:focus:ring-offset-zinc-900"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="edit-post-cancel" class="rounded-xl border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">Cancel</button>
                    <button type="submit" id="edit-post-submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
