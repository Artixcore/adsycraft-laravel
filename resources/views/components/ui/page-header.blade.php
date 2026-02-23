@props([
    'title' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'mb-6']) }}>
    @if($title)
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $title }}</h1>
    @endif
    @if($description)
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ $description }}</p>
    @endif
    @if(isset($actions))
        <div class="mt-4 flex flex-wrap gap-2">
            {{ $actions }}
        </div>
    @endif
    {{ $slot }}
</div>
