@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Roles & permissions</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Application roles (UI display only; permission logic is unchanged).</p>
    </div>

    <x-card title="Available roles">
        <ul class="space-y-2">
            @foreach($roles as $role)
                <li class="flex items-center gap-2 text-sm">
                    <x-badge variant="neutral">{{ $role }}</x-badge>
                </li>
            @endforeach
        </ul>
    </x-card>
</div>
@endsection
