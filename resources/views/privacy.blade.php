@extends('layouts.marketing')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
    <h1 class="text-4xl sm:text-5xl font-bold text-zinc-900 dark:text-white">
        Privacy Policy
    </h1>
    <p class="mt-6 text-sm text-zinc-500 dark:text-zinc-500">
        Last updated: {{ now()->format('F j, Y') }}
    </p>
    <div class="mt-12 prose prose-zinc dark:prose-invert max-w-none">
        <p class="text-zinc-600 dark:text-zinc-400">
            AdsyCraft ("we", "our", or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, and safeguard your information when you use our service.
        </p>
        <h2 class="mt-8 text-xl font-semibold text-zinc-900 dark:text-white">Information We Collect</h2>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">
            We collect information you provide directly (account details, brand voice settings, content) and data from connected services (Meta/Facebook) via official OAuth APIs. We do not scrape data.
        </p>
        <h2 class="mt-8 text-xl font-semibold text-zinc-900 dark:text-white">How We Use Your Information</h2>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">
            Your data is used to provide and improve AdsyCraft, generate content, publish to Meta platforms, and deliver insights. We do not sell your data to third parties.
        </p>
        <h2 class="mt-8 text-xl font-semibold text-zinc-900 dark:text-white">Data Security</h2>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">
            We use industry-standard security measures to protect your information. Meta connections use secure OAuth—we never store your Meta passwords.
        </p>
        <h2 class="mt-8 text-xl font-semibold text-zinc-900 dark:text-white">Contact</h2>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">
            For privacy-related questions, contact us at <a href="https://artixcore.com" target="_blank" rel="noopener" class="text-indigo-600 dark:text-indigo-400 hover:underline">artixcore.com</a>.
        </p>
    </div>
    <div class="mt-16 pt-12 border-t border-zinc-200 dark:border-zinc-800">
        <p class="text-sm text-zinc-500 dark:text-zinc-500">
            Developed by <a href="https://artixcore.com" target="_blank" rel="noopener" class="text-zinc-700 dark:text-zinc-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">Artixcore</a> – <a href="https://artixcore.com" target="_blank" rel="noopener" class="text-zinc-700 dark:text-zinc-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">artixcore.com</a>
        </p>
    </div>
</div>
@endsection
