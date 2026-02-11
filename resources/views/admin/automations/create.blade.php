@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Create automation</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a new business automation. Use the user dashboard to create businesses and connect Meta.</p>
    </div>

    <x-card title="Redirect to user dashboard">
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Business creation and Meta connection are performed in the user dashboard. As an admin you can view and manage automations from the list.</p>
        <x-button href="{{ route('dashboard') }}" variant="primary">Go to dashboard</x-button>
    </x-card>
</div>
@endsection
