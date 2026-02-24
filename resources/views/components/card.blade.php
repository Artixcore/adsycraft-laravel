@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-[var(--shadow-card-soft,0_1px_3px_0_rgb(0_0_0/0.05))] overflow-hidden']) }}>
    @if($title)
        <div class="border-b border-zinc-200 dark:border-zinc-800 px-4 py-3 sm:px-6">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-white">{{ $title }}</h3>
        </div>
    @endif
    <div class="p-4 sm:p-6">
        {{ $slot }}
    </div>
</div>
