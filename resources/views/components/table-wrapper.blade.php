@props([
    'empty' => false,
    'emptyMessage' => 'No data yet.',
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-gray-200 dark:border-[#252523] bg-white dark:bg-[#111110]']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-[#252523]">
            {{ $slot }}
        </table>
    </div>
    @if($empty && isset($emptySlot))
        <div class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">
            {{ $emptySlot }}
        </div>
    @elseif($empty)
        <div class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">
            {{ $emptyMessage }}
        </div>
    @endif
</div>
