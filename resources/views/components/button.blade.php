@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:pointer-events-none';
    $classes = match($variant) {
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500 dark:bg-indigo-600 dark:hover:bg-indigo-500',
        'secondary' => 'border border-gray-300 dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-[#1c1c1a] focus:ring-gray-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 dark:bg-red-600 dark:hover:bg-red-500',
        'ghost' => 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1c1c1a] focus:ring-gray-500',
        default => 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500',
    };
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "{$base} {$classes}"]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "{$base} {$classes}"]) }}>
        {{ $slot }}
    </button>
@endif
