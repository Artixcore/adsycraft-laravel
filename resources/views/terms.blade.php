@extends('layouts.marketing')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
    <h1 class="text-4xl sm:text-5xl font-bold text-zinc-900 dark:text-white">
        Terms of Service
    </h1>
    <p class="mt-6 text-sm text-zinc-500 dark:text-zinc-500">
        Last updated: {{ now()->format('F j, Y') }}
    </p>
    <div class="mt-12 prose prose-zinc dark:prose-invert max-w-none">
        <p class="text-zinc-600 dark:text-zinc-400">
            By using AdsyCraft ("the Service"), you agree to these Terms of Service. The Service is provided by Artixcore.
        </p>
        <h2 class="mt-8 text-xl font-semibold text-zinc-900 dark:text-white">Use of the Service</h2>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">
            You must use AdsyCraft in compliance with Meta's Platform Terms, Advertising Policies, and Community Standards. You are responsible for the content you create and publish through the Service.
        </p>
        <h2 class="mt-8 text-xl font-semibold text-zinc-900 dark:text-white">Subscription and Cancellation</h2>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">
            Paid plans are billed according to your selected tier. You may cancel at any time. There are no long-term contracts or lock-in periods.
        </p>
        <h2 class="mt-8 text-xl font-semibold text-zinc-900 dark:text-white">Limitation of Liability</h2>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">
            AdsyCraft is provided "as is". We are not liable for indirect, incidental, or consequential damages arising from your use of the Service.
        </p>
        <h2 class="mt-8 text-xl font-semibold text-zinc-900 dark:text-white">Contact</h2>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">
            For questions about these terms, contact us at <a href="https://artixcore.com" target="_blank" rel="noopener" class="text-indigo-600 dark:text-indigo-400 hover:underline">artixcore.com</a>.
        </p>
    </div>
    <div class="mt-16 pt-12 border-t border-zinc-200 dark:border-zinc-800">
        <p class="text-sm text-zinc-500 dark:text-zinc-500">
            Developed by <a href="https://artixcore.com" target="_blank" rel="noopener" class="text-zinc-700 dark:text-zinc-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">Artixcore</a> – <a href="https://artixcore.com" target="_blank" rel="noopener" class="text-zinc-700 dark:text-zinc-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">artixcore.com</a>
        </p>
    </div>
</div>
@endsection
