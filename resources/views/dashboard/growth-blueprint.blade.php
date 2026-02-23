@extends('layouts.app')

@push('vite')
    @vite(['resources/js/dashboard.js', 'resources/js/growth-blueprint.js'])
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <x-ui.page-header
        title="Growth Blueprint"
        description="AI-powered market research, positioning, and content strategy"
    />

    <x-card title="Generate Growth Blueprint">
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
            The Market Disruptor AI analyzes your market, competitors, and trends to produce a full Growth Blueprint:
            market map, trend radar, positioning, 30-day content calendar, and ads plan.
        </p>
        <div class="flex flex-wrap gap-3">
            <select id="blueprint-business-select" class="rounded-xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm">
                <option value="">Select a business</option>
            </select>
            <button type="button" id="btn-generate-blueprint" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900">
                Generate Blueprint
            </button>
        </div>
        <p id="blueprint-message" class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 hidden"></p>
    </x-card>

    <x-card title="Recent Blueprints">
        <div id="blueprint-list" class="space-y-2">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Select a business to view blueprints.</p>
        </div>
    </x-card>

    <x-card title="Blueprint Detail" id="blueprint-detail-card" class="hidden">
        <div id="blueprint-detail-content" class="prose dark:prose-invert max-w-none">
        </div>
    </x-card>
</div>
@endsection
