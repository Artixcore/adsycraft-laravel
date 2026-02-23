@extends('layouts.marketing')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
    <h1 class="text-4xl sm:text-5xl font-bold text-zinc-900 dark:text-white">
        Frequently Asked Questions
    </h1>

    <div class="mt-12 space-y-4">
        <details class="group rounded-xl border border-zinc-200 dark:border-[#27272A] bg-white dark:bg-[#161616] overflow-hidden">
            <summary class="flex items-center justify-between cursor-pointer list-none px-6 py-4 text-left font-medium text-zinc-900 dark:text-white hover:bg-zinc-50 dark:hover:bg-[#1a1a1a] transition">
                What is AdsyCraft?
                <span class="shrink-0 ml-2 transition group-open:rotate-180">
                    <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </span>
            </summary>
            <div class="px-6 pb-4 text-zinc-600 dark:text-zinc-400">
                AdsyCraft is a Growth Operating System for social media. It combines AI content generation, Meta (Facebook and Instagram) connectivity, and autopilot scheduling into one dashboard. You set your brand voice, connect your accounts, and let the system generate and schedule content for you.
            </div>
        </details>

        <details class="group rounded-xl border border-zinc-200 dark:border-[#27272A] bg-white dark:bg-[#161616] overflow-hidden">
            <summary class="flex items-center justify-between cursor-pointer list-none px-6 py-4 text-left font-medium text-zinc-900 dark:text-white hover:bg-zinc-50 dark:hover:bg-[#1a1a1a] transition">
                Which AI providers are supported?
                <span class="shrink-0 ml-2 transition group-open:rotate-180">
                    <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </span>
            </summary>
            <div class="px-6 pb-4 text-zinc-600 dark:text-zinc-400">
                AdsyCraft supports OpenAI, Google Gemini, and Grok. You can connect one or more providers and choose a primary for content generation. Each provider uses your own API key for full control.
            </div>
        </details>

        <details class="group rounded-xl border border-zinc-200 dark:border-[#27272A] bg-white dark:bg-[#161616] overflow-hidden">
            <summary class="flex items-center justify-between cursor-pointer list-none px-6 py-4 text-left font-medium text-zinc-900 dark:text-white hover:bg-zinc-50 dark:hover:bg-[#1a1a1a] transition">
                How does autopilot work?
                <span class="shrink-0 ml-2 transition group-open:rotate-180">
                    <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </span>
            </summary>
            <div class="px-6 pb-4 text-zinc-600 dark:text-zinc-400">
                Autopilot uses your brand voice and niche to generate content on a schedule you define. Set posts per day and timezone, and AdsyCraft will create and schedule content to your connected Meta pages and Instagram accounts automatically.
            </div>
        </details>

        <details class="group rounded-xl border border-zinc-200 dark:border-[#27272A] bg-white dark:bg-[#161616] overflow-hidden">
            <summary class="flex items-center justify-between cursor-pointer list-none px-6 py-4 text-left font-medium text-zinc-900 dark:text-white hover:bg-zinc-50 dark:hover:bg-[#1a1a1a] transition">
                Can I cancel anytime?
                <span class="shrink-0 ml-2 transition group-open:rotate-180">
                    <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </span>
            </summary>
            <div class="px-6 pb-4 text-zinc-600 dark:text-zinc-400">
                Yes. You can cancel your subscription at any time. There are no long-term contracts or lock-in periods.
            </div>
        </details>

        <details class="group rounded-xl border border-zinc-200 dark:border-[#27272A] bg-white dark:bg-[#161616] overflow-hidden">
            <summary class="flex items-center justify-between cursor-pointer list-none px-6 py-4 text-left font-medium text-zinc-900 dark:text-white hover:bg-zinc-50 dark:hover:bg-[#1a1a1a] transition">
                Is there a free trial?
                <span class="shrink-0 ml-2 transition group-open:rotate-180">
                    <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </span>
            </summary>
            <div class="px-6 pb-4 text-zinc-600 dark:text-zinc-400">
                Yes. You can start with a free trial—no credit card required. Sign up and explore Mission Control before committing to a paid plan.
            </div>
        </details>
    </div>
</div>
@endsection
