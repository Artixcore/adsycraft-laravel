@props([
    'label' => null,
    'error' => null,
])

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    @if($label)
        <x-input-label :for="$attributes->get('id', '')" :value="$label" class="mb-1.5 block" />
    @endif
    <select
        {{ $attributes->except('class')->merge([
            'class' => 'block w-full rounded-2xl border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition py-2.5 px-4 text-sm disabled:opacity-50 disabled:cursor-not-allowed ' . ($error ? 'border-red-500 dark:border-red-500' : ''),
        ]) }}
    >
        {{ $slot }}
    </select>
    @if($error)
        <x-input-error :messages="is_array($error) ? $error : [$error]" class="mt-1.5" />
    @endif
</div>
