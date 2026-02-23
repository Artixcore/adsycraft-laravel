@extends('layouts.app')

@push('vite')
    @vite(['resources/js/ad-library.js'])
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Disclaimer banner --}}
    <div class="rounded-lg border border-amber-200 dark:border-amber-900/50 bg-amber-50 dark:bg-amber-950/30 px-4 py-3">
        <p class="text-sm text-amber-800 dark:text-amber-200" id="ad-library-disclaimer">
            Data from Meta Ad Library. For competitive research and inspiration only. Subject to Meta's Terms of Service and Ad Library API policies.
        </p>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Main content: search + results --}}
        <div class="flex-1 min-w-0 space-y-6">
            {{-- Search bar + filters --}}
            <x-card title="Search Meta Ad Library">
                <form id="ad-library-search-form" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="search-query" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keywords / Page name</label>
                            <input type="text" id="search-query" name="query" placeholder="e.g. california, brand name" maxlength="100"
                                class="w-full rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="search-country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Country *</label>
                            <select id="search-country" name="countries" required multiple
                                class="w-full rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                size="3">
                                <option value="US">United States</option>
                                <option value="BD">Bangladesh</option>
                                <option value="GB">United Kingdom</option>
                                <option value="CA">Canada</option>
                                <option value="AU">Australia</option>
                                <option value="DE">Germany</option>
                                <option value="FR">France</option>
                                <option value="IN">India</option>
                                <option value="BR">Brazil</option>
                                <option value="MX">Mexico</option>
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Hold Ctrl/Cmd to select multiple</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="search-status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Active status</label>
                            <select id="search-status" name="ad_active_status"
                                class="w-full rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="ACTIVE">Active</option>
                                <option value="INACTIVE">Inactive</option>
                                <option value="ALL">All</option>
                            </select>
                        </div>
                        <div>
                            <label for="search-started-after" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Started after</label>
                            <input type="date" id="search-started-after" name="started_after"
                                class="w-full rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="search-started-before" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Started before</label>
                            <input type="date" id="search-started-before" name="started_before"
                                class="w-full rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="search-media-type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Media type</label>
                            <select id="search-media-type" name="media_type"
                                class="w-full rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">All</option>
                                <option value="IMAGE">Image</option>
                                <option value="VIDEO">Video</option>
                                <option value="MEME">Meme</option>
                                <option value="NONE">None</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <x-button type="submit" variant="primary" id="btn-search">Search</x-button>
                        <x-button type="button" variant="secondary" id="btn-save-search">Save search</x-button>
                    </div>
                </form>
            </x-card>

            {{-- Results grid --}}
            <x-card title="Results">
                <div id="ad-library-results" class="space-y-4">
                    <p id="results-placeholder" class="text-sm text-gray-500 dark:text-gray-400">Enter keywords and country, then click Search.</p>
                    <div id="results-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 hidden"></div>
                    <div id="results-loading" class="hidden text-sm text-gray-500 dark:text-gray-400">Loading…</div>
                    <div id="results-error" class="hidden text-sm text-red-600 dark:text-red-400"></div>
                    <div id="results-load-more" class="hidden text-center pt-4">
                        <x-button type="button" variant="secondary" id="btn-load-more">Load more</x-button>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Collections sidebar --}}
        <aside class="w-full lg:w-72 flex-shrink-0 space-y-4">
            <x-card title="Collections">
                <div class="space-y-3">
                    <form id="create-collection-form" class="flex gap-2">
                        <input type="text" id="new-collection-name" placeholder="New collection" maxlength="255"
                            class="flex-1 rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <x-button type="submit" variant="primary" class="shrink-0">Create</x-button>
                    </form>
                    <div id="collections-list" class="space-y-1">
                        <p class="text-sm text-gray-500 dark:text-gray-400">No collections yet.</p>
                    </div>
                </div>
            </x-card>
            <x-card title="Collection items" id="collection-items-card">
                <div id="collection-items-list" class="space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Select a collection to view items.</p>
                </div>
            </x-card>
        </aside>
    </div>
</div>

<template id="ad-card-template">
    <div class="rounded-lg border border-gray-200 dark:border-[#252523] bg-white dark:bg-[#161615] p-4" data-ad-id="">
        <div class="flex justify-between items-start gap-2 mb-2">
            <h4 class="font-medium text-gray-900 dark:text-white truncate"></h4>
            <span class="text-xs text-gray-500 dark:text-gray-400 shrink-0"></span>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3"></p>
        <div class="flex flex-wrap gap-1 mb-3">
            <span class="platforms text-xs text-gray-500 dark:text-gray-400"></span>
        </div>
        <div class="flex gap-2">
            <a href="#" target="_blank" rel="noopener" class="ad-snapshot-link text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Open snapshot</a>
            <button type="button" class="btn-save-to-collection text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Save to collection</button>
        </div>
    </div>
</template>
@endsection
