<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 border border-transparent font-semibold text-sm text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition']) }}>
    {{ $slot }}
</button>
