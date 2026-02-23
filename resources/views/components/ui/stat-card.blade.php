@props([
    'value' => null,
    'label' => null,
    'trend' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm']) }}>
    <div class="flex items-start justify-between">
        <div>
            @if($label)
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ $label }}</p>
            @endif
            @if($value !== null)
                <p class="mt-1 text-2xl font-bold text-zinc-900 dark:text-white">{{ $value }}</p>
            @else
                <div class="mt-1">{{ $slot }}</div>
            @endif
            @if($trend)
                <p class="mt-1 text-xs {{ str_starts_with($trend, '+') ? 'text-green-600 dark:text-green-400' : 'text-zinc-500 dark:text-zinc-400' }}">{{ $trend }}</p>
            @endif
        </div>
        @if(isset($icon))
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                {{ $icon }}
            </div>
        @endif
    </div>
</div>
