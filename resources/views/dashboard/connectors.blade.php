<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connectors – {{ config('app.name', 'MetaGrowth Autopilot') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/connectors.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen p-6">
    <div class="max-w-5xl mx-auto space-y-8">
        <header class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Connectors</h1>
            <div class="flex gap-4">
                <a href="{{ route('dashboard') }}" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:underline">Dashboard</a>
                <a href="/" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:underline">Home</a>
            </div>
        </header>

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
            <div id="meta-connector-section" class="space-y-2">
                <p id="meta-status" class="text-sm text-[#706f6c]">Not connected</p>
                <div class="flex gap-2">
                    <button type="button" id="btn-meta-connect" class="rounded border border-[#19140035] dark:border-[#3E3E3A] px-4 py-2 text-sm font-medium hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Connect</button>
                    <button type="button" id="btn-meta-disconnect" class="rounded border border-[#19140035] dark:border-[#3E3E3A] px-4 py-2 text-sm font-medium hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Disconnect</button>
                </div>
                <p id="meta-message" class="text-sm text-[#706f6c] hidden"></p>
            </div>
        </section>
    </div>
</body>
</html>
