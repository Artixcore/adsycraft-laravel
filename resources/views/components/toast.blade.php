@props([
    'type' => 'info',
    'message' => '',
    'dismissible' => true,
])

@php
    $variantClasses = [
        'success' => 'border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-950/40 text-green-800 dark:text-green-200',
        'error' => 'border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 text-red-800 dark:text-red-200',
        'warning' => 'border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-200',
        'info' => 'border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-950/40 text-blue-800 dark:text-blue-200',
    ];
    $classes = $variantClasses[$type] ?? $variantClasses['info'];
@endphp

<div
    {{ $attributes->merge(['class' => "flex items-start gap-3 rounded-xl border px-4 py-3 shadow-md pointer-events-auto {$classes}"]) }}
    data-toast="{{ $type }}"
    role="alert"
>
    @if($type === 'success')
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    @elseif($type === 'error')
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    @elseif($type === 'warning')
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    @else
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    @endif
    <p class="flex-1 text-sm font-medium">{{ $message ?: $slot }}</p>
    @if($dismissible)
        <button type="button" data-toast-dismiss aria-label="Dismiss" class="shrink-0 rounded-lg p-1 opacity-70 hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-offset-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    @endif
</div>
