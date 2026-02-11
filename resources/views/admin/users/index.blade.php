@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Users</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage application users.</p>
        </div>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-[#252523]">
                <thead class="bg-gray-50 dark:bg-[#161615]">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last login</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-[#252523]">
                    @forelse($users as $user)
                        <tr class="bg-white dark:bg-[#111110]">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <x-badge :variant="$user->role->value === 'admin' ? 'info' : 'neutral'">{{ $user->role->value }}</x-badge>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $user->last_login_at?->diffForHumans() ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="mt-4 border-t border-gray-200 dark:border-[#252523] pt-4">
                {{ $users->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection
