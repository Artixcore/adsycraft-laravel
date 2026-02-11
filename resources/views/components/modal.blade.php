@props([
    'id' => 'modal',
    'title' => null,
])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title">
    <div class="fixed inset-0 bg-gray-900/60 dark:bg-black/60" data-modal-backdrop></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div {{ $attributes->merge(['class' => 'relative w-full max-w-lg rounded-xl border border-gray-200 dark:border-[#252523] bg-white dark:bg-[#111110] shadow-xl']) }}>
            @if($title)
                <div class="border-b border-gray-200 dark:border-[#252523] px-6 py-4">
                    <h2 id="{{ $id }}-title" class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h2>
                </div>
            @endif
            <div class="px-6 py-4">
                {{ $slot }}
            </div>
            @if(isset($footer))
                <div class="border-t border-gray-200 dark:border-[#252523] px-6 py-4 flex justify-end gap-3">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
