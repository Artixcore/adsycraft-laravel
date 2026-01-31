<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard – {{ config('app.name', 'MetaGrowth Autopilot') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/dashboard.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen p-6">
    <div class="max-w-5xl mx-auto space-y-8">
        <header class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">MetaGrowth Autopilot</h1>
            <div class="flex gap-4">
                <a href="{{ route('dashboard.connectors') }}" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:underline">Connectors</a>
                <a href="/" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:underline">Home</a>
            </div>
        </header>

        <section>
            <h2 class="text-lg font-medium mb-3">Businesses</h2>
            <div id="business-list" class="space-y-2 mb-4">
                <p class="text-sm text-[#706f6c]">Loading…</p>
            </div>
        </section>

        <section>
            <h2 class="text-lg font-medium mb-3">Create business</h2>
            <form id="create-business-form" class="space-y-3 max-w-md">
                <div>
                    <label class="block text-sm font-medium mb-1">Name *</label>
                    <input type="text" name="name" required class="w-full rounded border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Niche</label>
                    <input type="text" name="niche" class="w-full rounded border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Website URL</label>
                    <input type="url" name="website_url" class="w-full rounded border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tone</label>
                    <input type="text" name="tone" class="w-full rounded border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Language</label>
                    <input type="text" name="language" placeholder="e.g. en" class="w-full rounded border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Posts per day</label>
                    <input type="number" name="posts_per_day" min="1" max="20" value="1" class="w-full rounded border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Timezone *</label>
                    <input type="text" name="timezone" required placeholder="e.g. America/New_York" value="UTC" class="w-full rounded border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="autopilot_enabled" id="autopilot_enabled" value="1" class="rounded">
                    <label for="autopilot_enabled" class="text-sm">Autopilot enabled</label>
                </div>
                <button type="submit" class="rounded border border-[#19140035] dark:border-[#3E3E3A] px-4 py-2 text-sm font-medium hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Create</button>
            </form>
            <p id="create-message" class="mt-2 text-sm text-[#706f6c] hidden"></p>
        </section>

        <section id="selected-business-section" class="hidden">
            <h2 class="text-lg font-medium mb-3">Selected business: <span id="selected-business-name"></span></h2>
            <div class="flex flex-wrap gap-2 mb-4">
                <button type="button" id="btn-generate-today" class="rounded border border-[#19140035] dark:border-[#3E3E3A] px-4 py-2 text-sm font-medium hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Generate today</button>
                <button type="button" id="btn-toggle-autopilot" class="rounded border border-[#19140035] dark:border-[#3E3E3A] px-4 py-2 text-sm font-medium hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">Toggle autopilot</button>
            </div>
            <p id="selected-message" class="text-sm text-[#706f6c] mb-2"></p>

            <h3 class="text-base font-medium mb-2">Posts</h3>
            <div id="posts-container">
                <table class="w-full text-sm border border-gray-200 dark:border-[#3E3E3A] rounded overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-[#161615]">
                        <tr>
                            <th class="text-left p-2">ID</th>
                            <th class="text-left p-2">Status</th>
                            <th class="text-left p-2">Scheduled</th>
                            <th class="text-left p-2">Caption</th>
                        </tr>
                    </thead>
                    <tbody id="posts-tbody">
                        <tr><td colspan="4" class="p-2 text-[#706f6c]">No posts</td></tr>
                    </tbody>
                </table>
            </div>

            <h3 class="text-base font-medium mt-4 mb-2">Calendar feed</h3>
            <div id="calendar-container">
                <ul id="calendar-list" class="list-disc list-inside text-sm space-y-1">
                    <li class="text-[#706f6c]">No events</li>
                </ul>
            </div>
        </section>
    </div>
</body>
</html>
