@extends('layouts.app')

@push('vite')
    @vite(['resources/js/ads.js'])
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <x-card title="Select business">
        <div id="business-selector" class="space-y-2">
            <p class="text-sm text-gray-500 dark:text-gray-400">Loading…</p>
        </div>
    </x-card>

    <div id="ads-content" class="hidden space-y-8">
        <x-card title="Ad Accounts">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Connect Meta in <a href="{{ route('dashboard.connectors') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Connectors</a> to access your ad accounts.
            </p>
            <div id="ad-accounts-loading" class="hidden text-sm text-gray-500 dark:text-gray-400">Loading ad accounts…</div>
            <div id="ad-accounts-list" class="space-y-2">
                <p id="ad-accounts-placeholder" class="text-sm text-gray-500 dark:text-gray-400">Select a business to load ad accounts.</p>
            </div>
        </x-card>

        <x-card title="Campaign Builder">
            <p class="text-sm text-gray-500 dark:text-gray-400">Coming soon. Connect your ad account above to get started when the campaign builder is available.</p>
        </x-card>
    </div>
</div>
@endsection
