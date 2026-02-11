@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Settings</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Organized settings sections (UI only).</p>
    </div>

    <x-card title="General">
        <p class="text-sm text-gray-600 dark:text-gray-400">Application name and general configuration. No logic changes.</p>
    </x-card>

    <x-card title="Integrations">
        <p class="text-sm text-gray-600 dark:text-gray-400">Meta, AI providers, and other integrations. Configure via user Connectors.</p>
    </x-card>

    <x-card title="Security">
        <p class="text-sm text-gray-600 dark:text-gray-400">Roles and permissions are managed by the application. No changes from this UI.</p>
    </x-card>
</div>
@endsection
