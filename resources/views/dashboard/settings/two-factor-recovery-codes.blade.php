@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <x-ui.page-header title="Recovery Codes" description="Store these codes in a secure place. Each can be used once." />

    <x-card>
        <div class="grid grid-cols-2 gap-2 font-mono text-sm">
            @foreach($codes as $code)
                <div class="rounded-lg bg-zinc-100 dark:bg-zinc-800 px-3 py-2">{{ $code }}</div>
            @endforeach
        </div>
        <form method="POST" action="{{ route('two-factor.regenerate-recovery-codes') }}" class="mt-6">
            @csrf
            <x-primary-button type="submit">Regenerate codes</x-primary-button>
        </form>
        <a href="{{ route('dashboard.settings') }}" class="inline-block mt-4 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Back to Settings</a>
    </x-card>
</div>
@endsection
