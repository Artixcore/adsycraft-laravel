@props([
    'variant' => 'info',
])

@php
    $wrapper = match($variant) {
        'success' => 'bg-green-50 dark:bg-green-950/30 border-green-200 dark:border-green-800 text-green-800 dark:text-green-300',
        'warning' => 'bg-amber-50 dark:bg-amber-950/30 border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300',
        'error' => 'bg-red-50 dark:bg-red-950/30 border-red-200 dark:border-red-800 text-red-800 dark:text-red-300',
        default => 'bg-indigo-50 dark:bg-indigo-950/30 border-indigo-200 dark:border-indigo-800 text-indigo-800 dark:text-indigo-300',
    };
@endphp

<div {{ $attributes->merge(['class' => "rounded-lg border p-4 {$wrapper}", 'role' => 'alert']) }}>
    {{ $slot }}
</div>
