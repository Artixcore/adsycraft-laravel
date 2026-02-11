@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-gray-200 dark:border-[#252523] bg-white dark:bg-[#111110] shadow-sm overflow-hidden']) }}>
    @if($title)
        <div class="border-b border-gray-200 dark:border-[#252523] px-4 py-3 sm:px-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
        </div>
    @endif
    <div class="p-4 sm:p-6">
        {{ $slot }}
    </div>
</div>
