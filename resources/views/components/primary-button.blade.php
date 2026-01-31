<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-lg font-semibold text-sm text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition']) }}>
    {{ $slot }}
</button>
