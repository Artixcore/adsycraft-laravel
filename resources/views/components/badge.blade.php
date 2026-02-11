@props([
    'variant' => 'neutral',
])

@php
    $classes = match($variant) {
        'success' => 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300 border-green-200 dark:border-green-800',
        'warning' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800',
        'error', 'danger' => 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300 border-red-200 dark:border-red-800',
        'info' => 'bg-indigo-100 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800',
        default => 'bg-gray-100 dark:bg-[#1c1c1a] text-gray-700 dark:text-gray-300 border-gray-200 dark:border-[#252523]',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-medium {$classes}"]) }}>
    {{ $slot }}
</span>
