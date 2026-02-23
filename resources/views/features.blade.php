@extends('layouts.marketing')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
    <div class="text-center mb-16">
        <h1 class="text-4xl sm:text-5xl font-bold text-zinc-900 dark:text-white">
            Everything you need to scale
        </h1>
        <p class="mt-6 text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
            AdsyCraft brings AI content, Meta sync, and autopilot into one Growth Operating System.
        </p>
    </div>

    {{-- Feature comparison table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-[#27272A] bg-white dark:bg-[#161616]">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-[#27272A]">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-zinc-900 dark:text-white">Feature</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-zinc-900 dark:text-white">Starter</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-zinc-900 dark:text-white">
                        <span class="inline-flex items-center gap-1">Growth <span class="text-xs font-medium text-indigo-600 dark:text-indigo-400">Most popular</span></span>
                    </th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-zinc-900 dark:text-white">Scale</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-[#27272A]">
                <tr>
                    <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">AI Content Engine</td>
                    <td class="px-6 py-4 text-center text-indigo-600 dark:text-indigo-400">✓</td>
                    <td class="px-6 py-4 text-center text-indigo-600 dark:text-indigo-400">✓</td>
                    <td class="px-6 py-4 text-center text-indigo-600 dark:text-indigo-400">✓</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">Meta Connector</td>
                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400">1 account</td>
                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400">3 accounts</td>
                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400">Unlimited</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">Autopilot</td>
                    <td class="px-6 py-4 text-center text-indigo-600 dark:text-indigo-400">✓</td>
                    <td class="px-6 py-4 text-center text-indigo-600 dark:text-indigo-400">✓</td>
                    <td class="px-6 py-4 text-center text-indigo-600 dark:text-indigo-400">✓</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">Brand Voices</td>
                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400">1</td>
                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400">3</td>
                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400">10</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">Posts/month</td>
                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400">30</td>
                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400">150</td>
                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400">500</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">Research</td>
                    <td class="px-6 py-4 text-center text-zinc-400 dark:text-zinc-500">—</td>
                    <td class="px-6 py-4 text-center text-indigo-600 dark:text-indigo-400">✓</td>
                    <td class="px-6 py-4 text-center text-indigo-600 dark:text-indigo-400">✓</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">Priority support</td>
                    <td class="px-6 py-4 text-center text-zinc-400 dark:text-zinc-500">—</td>
                    <td class="px-6 py-4 text-center text-zinc-400 dark:text-zinc-500">—</td>
                    <td class="px-6 py-4 text-center text-indigo-600 dark:text-indigo-400">✓</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-12 text-center">
        <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white hover:bg-indigo-700 transition">View pricing</a>
    </div>

    {{-- Feature deep-dive --}}
    <div class="mt-24 space-y-20">
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="shrink-0 inline-flex items-center justify-center w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">AI Content Engine</h2>
                <p class="mt-4 text-zinc-600 dark:text-zinc-400">Connect OpenAI, Gemini, or Grok. Set your brand voice—tone, language, style—and let AI generate on-brand content. No more staring at a blank screen.</p>
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="shrink-0 inline-flex items-center justify-center w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Meta Connector</h2>
                <p class="mt-4 text-zinc-600 dark:text-zinc-400">Link Facebook Pages and Instagram in one OAuth flow. Publish and schedule to both platforms from a single dashboard. No switching between apps.</p>
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="shrink-0 inline-flex items-center justify-center w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Autopilot Scheduling</h2>
                <p class="mt-4 text-zinc-600 dark:text-zinc-400">Define posts per day and timezone. Autopilot generates content and schedules it automatically. You focus on strategy; AdsyCraft handles execution.</p>
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="shrink-0 inline-flex items-center justify-center w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v6m4 0v-2m-4 2v-2" /></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Brand Voice</h2>
                <p class="mt-4 text-zinc-600 dark:text-zinc-400">One voice definition per business. Tone, niche, and language stay consistent across every piece of content AI produces.</p>
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="shrink-0 inline-flex items-center justify-center w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Calendar & Insights</h2>
                <p class="mt-4 text-zinc-600 dark:text-zinc-400">See your content calendar at a glance. Track performance and refine your strategy with insights from your connected pages.</p>
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="shrink-0 inline-flex items-center justify-center w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Research & Intelligence</h2>
                <p class="mt-4 text-zinc-600 dark:text-zinc-400">Product and audience research to inform your content decisions. Understand your market before you post.</p>
            </div>
        </div>
    </div>

    <div class="mt-20 text-center">
        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white hover:bg-indigo-700 transition">Launch Mission Control</a>
    </div>
</div>
@endsection
