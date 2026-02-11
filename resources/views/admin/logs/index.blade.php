@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Logs & history</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Audit log (filterable).</p>
    </div>

    <x-card>
        <form method="GET" action="{{ route('admin.logs.index') }}" class="flex flex-wrap gap-3 mb-4">
            <input type="text" name="action" value="{{ request('action') }}" placeholder="Action" class="rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm w-32 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <input type="date" name="from" value="{{ request('from') }}" class="rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <input type="date" name="to" value="{{ request('to') }}" class="rounded-lg border border-gray-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <x-button type="submit" variant="secondary">Filter</x-button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-[#252523]">
                <thead class="bg-gray-50 dark:bg-[#161615]">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Resource</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-[#252523]">
                    @forelse($logs as $log)
                        <tr class="bg-white dark:bg-[#111110]">
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $log->created_at->format('M j, Y H:i:s') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $log->user?->email ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <x-badge variant="neutral">{{ $log->action }}</x-badge>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $log->resource_type ? $log->resource_type . ' #' . $log->resource_id : '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No log entries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection
