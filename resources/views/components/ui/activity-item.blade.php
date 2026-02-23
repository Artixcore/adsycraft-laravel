@props([
    'title' => null,
    'time' => null,
    'meta' => null,
])

<div {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-xl px-3 py-2.5 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition']) }}>
    @if(isset($icon))
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400">
            {{ $icon }}
        </div>
    @endif
    <div class="min-w-0 flex-1">
        @if($title)
            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $title }}</p>
        @endif
        @if(isset($slot) && trim($slot))
            <div class="text-sm text-zinc-600 dark:text-zinc-400">{{ $slot }}</div>
        @endif
        <div class="mt-0.5 flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-500">
            @if($time)
                <span>{{ $time }}</span>
            @endif
            @if($meta)
                <span>·</span>
                <span>{{ $meta }}</span>
            @endif
        </div>
    </div>
</div>
