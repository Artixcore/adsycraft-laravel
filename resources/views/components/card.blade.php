@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden']) }}>
    @if($title)
        <div class="border-b border-zinc-200 dark:border-zinc-800 px-4 py-3 sm:px-6">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-white">{{ $title }}</h3>
        </div>
    @endif
    <div class="p-4 sm:p-6">
        {{ $slot }}
    </div>
</div>
