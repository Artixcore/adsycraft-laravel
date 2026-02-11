@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Automations</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Business accounts and automation status.</p>
        </div>
        <x-button href="{{ route('admin.automations.create') }}" variant="primary">Create automation</x-button>
    </div>

    <x-card>
        <form method="GET" action="{{ route('admin.automations.index') }}" class="flex flex-wrap gap-3 mb-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." class="rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm w-48 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <select name="status" class="rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">All statuses</option>
                <option value="autopilot" {{ request('status') === 'autopilot' ? 'selected' : '' }}>Autopilot on</option>
                <option value="paused" {{ request('status') === 'paused' ? 'selected' : '' }}>Paused</option>
            </select>
            <x-button type="submit" variant="secondary">Filter</x-button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-[#252523]">
                <thead class="bg-gray-50 dark:bg-[#161615]">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Updated</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-[#252523]">
                    @forelse($automations as $automation)
                        <tr class="bg-white dark:bg-[#111110]">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $automation->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $automation->user?->email ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($automation->autopilot_enabled)
                                    <x-badge variant="success">Active</x-badge>
                                @else
                                    <x-badge variant="neutral">Paused</x-badge>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $automation->updated_at->diffForHumans() }}</td>
                            <td class="px-4 py-3 text-right">
                                <x-button href="{{ route('admin.automations.show', $automation) }}" variant="ghost" class="!py-1.5 !px-2 !text-xs">View</x-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center">
                                <x-empty-state title="No automations yet" description="Create your first automation to get started.">
                                    <x-slot:action>
                                        <x-button href="{{ route('admin.automations.create') }}" variant="primary">Create automation</x-button>
                                    </x-slot:action>
                                </x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($automations->hasPages())
            <div class="mt-4 border-t border-gray-200 dark:border-[#252523] pt-4">
                {{ $automations->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection
