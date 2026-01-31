@extends('layouts.dashboard')

@push('vite')
    @vite(['resources/js/connectors.js'])
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <section>
        <h2 class="text-lg font-medium mb-3">Select business</h2>
        <div id="business-selector" class="space-y-2 mb-4">
            <p class="text-sm text-[#706f6c]">Loading…</p>
        </div>
    </section>

    <section id="connectors-content" class="hidden">
        <h2 class="text-lg font-medium mb-4">AI Providers</h2>
        <div id="ai-connections-list" class="space-y-3 mb-6">
            <p class="text-sm text-[#706f6c]">No AI connections yet.</p>
        </div>
        <div class="mb-6">
            <h3 class="text-base font-medium mb-2">Add connection</h3>
            <form id="add-ai-connection-form" class="space-y-3 max-w-md">
                <div>
                    <label class="block text-sm font-medium mb-1">Provider *</label>
                    <select name="provider" required class="w-full rounded border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm">
                        <option value="openai">OpenAI</option>
                        <option value="gemini">Gemini</option>
                        <option value="grok">Grok</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">API key *</label>
                    <input type="password" name="api_key" required minlength="10" placeholder="sk-… or your key" class="w-full rounded border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm" autocomplete="off">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Default model</label>
                    <input type="text" name="default_model" placeholder="e.g. gpt-4" class="w-full rounded border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_primary" id="add_is_primary" value="1" class="rounded">
                    <label for="add_is_primary" class="text-sm">Set as primary</label>
                </div>
                <button type="submit" class="rounded border border-[#19140035] dark:border-[#3E3E3A] px-4 py-2 text-sm font-medium hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Add</button>
            </form>
            <p id="ai-add-message" class="mt-2 text-sm text-[#706f6c] hidden"></p>
        </div>

        <h2 class="text-lg font-medium mb-3">Meta Connector</h2>
        <div id="meta-connector-section" class="space-y-3">
            <p id="meta-status" class="text-sm text-[#706f6c]">Not connected</p>
            <div class="flex gap-2">
                <button type="button" id="btn-meta-connect" class="rounded border border-[#19140035] dark:border-[#3E3E3A] px-4 py-2 text-sm font-medium hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Connect Meta</button>
                <button type="button" id="btn-meta-disconnect" class="rounded border border-[#19140035] dark:border-[#3E3E3A] px-4 py-2 text-sm font-medium hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Disconnect</button>
            </div>
            <p id="meta-message" class="text-sm text-[#706f6c] hidden"></p>

            <div id="meta-assets-block" class="hidden mt-4">
                <h3 class="text-base font-medium mb-2">Discovered assets (Pages + Instagram)</h3>
                <div id="meta-assets-list" class="space-y-2 mb-3">
                    <p class="text-sm text-[#706f6c]">No pages yet. Connect Meta to discover.</p>
                </div>
                <button type="button" id="btn-meta-save-selection" class="rounded border border-[#19140035] dark:border-[#3E3E3A] px-4 py-2 text-sm font-medium hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Save Selection</button>
                <p id="meta-assets-message" class="text-sm text-[#706f6c] mt-2 hidden"></p>
            </div>
        </div>
    </section>
</div>
@endsection
