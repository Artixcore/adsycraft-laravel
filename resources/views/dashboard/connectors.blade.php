@extends('layouts.app')

@push('vite')
    @vite(['resources/js/connectors.js'])
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <x-card title="Select business">
        <div id="business-selector" class="space-y-2">
            <p class="text-sm text-gray-500 dark:text-gray-400">Loading…</p>
        </div>
    </x-card>

    <div id="connectors-content" class="hidden space-y-8">
        <x-card title="AI Providers">
            <div id="ai-connections-list" class="space-y-3 mb-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">No AI connections yet.</p>
            </div>
            <div class="border-t border-gray-200 dark:border-[#252523] pt-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Add connection</h3>
                <form id="add-ai-connection-form" class="space-y-4 max-w-md">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Provider *</label>
                        <select name="provider" required class="w-full rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="openai">OpenAI</option>
                            <option value="gemini">Gemini</option>
                            <option value="grok">Grok</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">API key *</label>
                        <input type="password" name="api_key" required minlength="10" placeholder="sk-… or your key" class="w-full rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" autocomplete="off">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default model</label>
                        <input type="text" name="default_model" placeholder="e.g. gpt-4" class="w-full rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_primary" id="add_is_primary" value="1" class="rounded border-gray-300 dark:border-[#3E3E3A] text-indigo-600 focus:ring-indigo-500">
                        <label for="add_is_primary" class="text-sm text-gray-700 dark:text-gray-300">Set as primary</label>
                    </div>
                    <x-button type="submit" variant="primary">Add</x-button>
                </form>
                <p id="ai-add-message" class="mt-2 text-sm hidden"></p>
            </div>
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
