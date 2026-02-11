@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $automation->name }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Automation details (read-only).</p>
        </div>
        <x-button href="{{ route('admin.automations.index') }}" variant="secondary">Back to list</x-button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-card title="Details">
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Name</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ $automation->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Niche</dt>
                    <dd class="text-gray-900 dark:text-white">{{ $automation->niche ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">User</dt>
                    <dd class="text-gray-900 dark:text-white">{{ $automation->user?->email ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Autopilot</dt>
                    <dd>
                        @if($automation->autopilot_enabled)
                            <x-badge variant="success">On</x-badge>
                        @else
                            <x-badge variant="neutral">Off</x-badge>
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Timezone</dt>
                    <dd class="text-gray-900 dark:text-white">{{ $automation->timezone ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Updated</dt>
                    <dd class="text-gray-900 dark:text-white">{{ $automation->updated_at->format('M j, Y H:i') }}</dd>
                </div>
            </dl>
        </x-card>
        <x-card title="Quick link">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Users manage this business from their dashboard. No logic changes from this admin view.</p>
            <x-button href="{{ route('dashboard') }}" variant="secondary">User dashboard</x-button>
        </x-card>
    </div>
</div>
@endsection
