@extends('layouts.app')

@push('vite')
    @vite(['resources/js/connectors.js'])
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    @if(request()->query('meta') === 'connected')
        <div id="meta-success-banner" class="rounded-lg border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-950/30 px-4 py-3 text-sm text-green-800 dark:text-green-200">
            Meta account connected successfully. Select your pages below.
        </div>
    @endif
    @if(request()->query('meta') === 'error')
        <div id="meta-error-banner" class="rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/30 px-4 py-3 text-sm text-red-800 dark:text-red-200">
            <span id="meta-error-reason">{{ request()->query('reason', 'Connection failed. Please try again.') }}</span>
        </div>
    @endif
    <x-card title="Select business">
        <div id="business-selector" class="space-y-2">
            <p class="text-sm text-gray-500 dark:text-gray-400">Loading…</p>
        </div>
    </x-card>

    <div id="connectors-content" class="hidden space-y-8">
        <x-card title="AI">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                @if($ai_configured ?? false)
                    <span class="text-green-600 dark:text-green-400">Configured.</span> AI providers are set via server environment variables.
                @else
                    <span class="text-amber-600 dark:text-amber-400">Not configured.</span> Set OPENAI_API_KEY, GEMINI_API_KEY, or GROK_API_KEY in .env.
                @endif
            </p>
        </x-card>

        <x-card title="Meta Connector">
            <div id="meta-connector-section" class="space-y-3">
                <p id="meta-status" class="text-sm text-gray-500 dark:text-gray-400">Not connected</p>
                <div class="flex flex-wrap gap-2">
                    <x-button type="button" id="btn-meta-connect" variant="primary">Connect Meta</x-button>
                    <x-button type="button" id="btn-meta-disconnect" variant="secondary">Disconnect</x-button>
                </div>
                <p id="meta-message" class="text-sm text-gray-500 dark:text-gray-400 hidden"></p>

                <div id="meta-assets-block" class="hidden mt-4 pt-4 border-t border-gray-200 dark:border-[#252523]">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Discovered assets (Pages + Instagram)</h3>
                    <div id="meta-assets-list" class="space-y-2 mb-3">
                        <p class="text-sm text-gray-500 dark:text-gray-400">No pages yet. Connect Meta to discover.</p>
                    </div>
                    <x-button type="button" id="btn-meta-save-selection" variant="secondary">Save Selection</x-button>
                    <p id="meta-assets-message" class="text-sm text-gray-500 dark:text-gray-400 mt-2 hidden"></p>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection
