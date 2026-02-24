@props([
    'id' => 'modal',
    'title' => null,
])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title">
    <div class="fixed inset-0 bg-gray-900/60 dark:bg-black/60" data-modal-backdrop></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div {{ $attributes->merge(['class' => 'relative w-full max-w-lg rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-xl']) }}>
            @if($title)
                <div class="border-b border-zinc-200 dark:border-zinc-700 px-6 py-4">
                    <h2 id="{{ $id }}-title" class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h2>
                </div>
            @endif
            <div class="px-6 py-4">
                {{ $slot }}
            </div>
            @if(isset($footer))
                <div class="border-t border-zinc-200 dark:border-zinc-700 px-6 py-4 flex justify-end gap-3">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
