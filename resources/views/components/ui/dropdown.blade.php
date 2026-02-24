@props([
    'align' => 'right',
])

@php
    $id = 'dropdown-' . Str::random(8);
@endphp

<div class="relative" data-dropdown id="{{ $id }}">
    <div data-dropdown-trigger aria-haspopup="true" aria-expanded="false">
        {{ $trigger ?? '' }}
    </div>
    <div
        data-dropdown-menu
        class="absolute z-50 mt-1 min-w-[12rem] rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-lg ring-1 ring-black/5 dark:ring-white/5 focus:outline-none {{ $align === 'right' ? 'right-0' : 'left-0' }} origin-top-{{ $align }} hidden"
        role="menu"
    >
        {{ $slot }}
    </div>
</div>
