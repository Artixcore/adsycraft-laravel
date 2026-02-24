@props([
    'label' => null,
    'checked' => false,
    'name' => null,
])

@php
    $id = $attributes->get('id') ?? 'toggle-' . Str::random(8);
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'flex items-center gap-3']) }}>
    <label
        for="{{ $id }}"
        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 dark:focus-within:ring-offset-zinc-900 {{ $checked ? 'bg-indigo-600' : 'bg-zinc-200 dark:bg-zinc-700' }}"
    >
        <input
            type="checkbox"
            id="{{ $id }}"
            name="{{ $name }}"
            value="1"
            {{ $checked ? 'checked' : '' }}
            role="switch"
            aria-checked="{{ $checked ? 'true' : 'false' }}"
            aria-label="{{ $label ?? __('Toggle') }}"
            {{ $attributes->except('class') }}
            class="sr-only peer"
        >
        <span
            aria-hidden="true"
            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out peer-checked:translate-x-5 translate-x-1"
        ></span>
    </label>
    @if($label)
        <label for="{{ $id }}" class="text-sm font-medium text-zinc-700 dark:text-zinc-300 cursor-pointer">
            {{ $label }}
        </label>
    @endif
</div>
