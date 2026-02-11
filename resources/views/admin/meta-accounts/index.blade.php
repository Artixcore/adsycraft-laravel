@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Meta accounts</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Connected Meta (Facebook/Instagram) pages and accounts.</p>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-[#252523]">
                <thead class="bg-gray-50 dark:bg-[#161615]">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Business</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Connected at</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-[#252523]">
                    @forelse($connections as $conn)
                        <tr class="bg-white dark:bg-[#111110]">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $conn->businessAccount?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $conn->businessAccount?->user?->email ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $conn->connected_at?->format('M j, Y H:i') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <x-badge variant="success">Connected</x-badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No Meta connections yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($connections->hasPages())
            <div class="mt-4 border-t border-gray-200 dark:border-[#252523] pt-4">
                {{ $connections->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection
