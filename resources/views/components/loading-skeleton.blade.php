@props([
    'lines' => 3,
])

@php
    $lines = (int) $lines;
    $lines = $lines >= 1 ? $lines : 3;
@endphp
<div {{ $attributes->merge(['class' => 'animate-pulse space-y-3']) }} aria-hidden="true">
    @for($i = 0; $i < $lines; $i++)
        <div class="h-4 rounded bg-gray-200 dark:bg-[#252523]" style="width: {{ $i === $lines - 1 && $lines > 1 ? '75%' : '100%' }}"></div>
    @endfor
</div>
