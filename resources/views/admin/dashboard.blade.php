@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Admin Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Overview of connected accounts, automations, and system health.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-card class="!p-5">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-100 dark:bg-indigo-950/50">
                    <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Connected Meta accounts</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $connectedMetaAccounts }}</p>
                </div>
            </div>
        </x-card>
        <x-card class="!p-5">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100 dark:bg-green-900/40">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active automations</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $activeAutomations }}</p>
                </div>
            </div>
        </x-card>
        <x-card class="!p-5">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/40">
                    <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Scheduled jobs</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $scheduledJobs }}</p>
                </div>
            </div>
        </x-card>
        <x-card class="!p-5">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $failedJobsCount > 0 ? 'bg-red-100 dark:bg-red-900/40' : 'bg-gray-100 dark:bg-[#1c1c1a]' }}">
                    <svg class="h-6 w-6 {{ $failedJobsCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Failed jobs</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $failedJobsCount }}</p>
                </div>
            </div>
        </x-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-card title="Daily overview">
            <dl class="space-y-3">
                <div class="flex justify-between text-sm">
                    <dt class="text-gray-500 dark:text-gray-400">Total users</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ $totalUsers }}</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-gray-500 dark:text-gray-400">Total businesses / automations</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ $totalBusinesses }}</dd>
                </div>
            </dl>
        </x-card>
        <x-card title="Quick actions">
            <div class="flex flex-wrap gap-2">
                <x-button href="{{ route('admin.automations.create') }}" variant="primary">Create Automation</x-button>
                <x-button href="{{ route('dashboard.connectors') }}" variant="secondary">Connect Account</x-button>
                <x-button href="{{ route('admin.logs.index') }}" variant="secondary">View Logs</x-button>
                <x-button href="{{ route('admin.users.index') }}" variant="secondary">Manage Users</x-button>
            </div>
        </x-card>
    </div>
</div>
@endsection
