@extends('layouts.marketing')

@section('content')
{{-- 1. Hero --}}
<section class="relative overflow-hidden border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Your Growth Operating System
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-zinc-600 dark:text-zinc-400 leading-relaxed">
                AI-powered content, Meta sync, and autopilot scheduling. One command center. Zero guesswork.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition active:scale-[0.98]">
                    Launch Mission Control
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl border border-zinc-300 dark:border-zinc-600 px-6 py-3.5 text-base font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition active:scale-[0.98]">
                    Sign In
                </a>
            </div>
            <p class="mt-4 text-sm text-zinc-500 dark:text-zinc-500">
                No credit card required · Free to start
            </p>
        </div>
    </div>
</section>

{{-- 2. Social proof strip --}}
<section class="py-12 border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-8">
            Trusted by creators and brands
        </p>
        <div class="flex flex-wrap items-center justify-center gap-8 sm:gap-12 opacity-60 grayscale">
            <div class="h-8 w-24 bg-zinc-300 dark:bg-zinc-600 rounded-lg" aria-hidden="true"></div>
            <div class="h-8 w-28 bg-zinc-300 dark:bg-zinc-600 rounded-lg" aria-hidden="true"></div>
            <div class="h-8 w-20 bg-zinc-300 dark:bg-zinc-600 rounded-lg" aria-hidden="true"></div>
            <div class="h-8 w-32 bg-zinc-300 dark:bg-zinc-600 rounded-lg" aria-hidden="true"></div>
            <div class="h-8 w-24 bg-zinc-300 dark:bg-zinc-600 rounded-lg" aria-hidden="true"></div>
        </div>
    </div>
</section>

{{-- 3. Problem → Solution --}}
<section class="py-20 lg:py-28 bg-white dark:bg-zinc-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-zinc-900 dark:text-white">
            Social growth shouldn't feel like guesswork
        </h2>
        <p class="mt-6 text-lg text-center text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
            Manual posting, inconsistent voice, wasted hours—and missed opportunities. Most brands struggle without a system.
        </p>
        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <div class="rounded-2xl border border-red-200 dark:border-red-900/50 bg-red-50/50 dark:bg-red-950/20 p-6 shadow-sm">
                <p class="text-sm font-semibold text-red-700 dark:text-red-400 mb-2">Without a system</p>
                <p class="text-zinc-600 dark:text-zinc-400">Scattered tools, manual scheduling, voice drift, and constant firefighting.</p>
            </div>
            <div class="rounded-2xl border border-green-200 dark:border-green-900/50 bg-green-50/50 dark:bg-green-950/20 p-6 shadow-sm">
                <p class="text-sm font-semibold text-green-700 dark:text-green-400 mb-2">With AdsyCraft</p>
                <p class="text-zinc-600 dark:text-zinc-400">One command center, AI that knows your brand, and autopilot that runs.</p>
            </div>
        </div>
    </div>
</section>

{{-- 4. Feature grid (6 features) --}}
<section class="py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-950">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-zinc-900 dark:text-white">
            Everything you need to scale
        </h2>
        <p class="mt-4 text-lg text-center text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
            From AI content to Meta sync—all in one place.
        </p>
        <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach([
                ['icon' => 'sparkles', 'title' => 'AI Content Engine', 'desc' => 'Generate on-brand posts with OpenAI, Gemini, or Grok. Set your voice once and let AI do the rest.'],
                ['icon' => 'link', 'title' => 'Meta Connector', 'desc' => 'Connect Facebook Pages and Instagram. Publish and schedule across both platforms from one dashboard.'],
                ['icon' => 'clock', 'title' => 'Autopilot Scheduling', 'desc' => 'Set posts per day and timezone. Autopilot generates and schedules content so you can focus on strategy.'],
                ['icon' => 'microphone', 'title' => 'Brand Voice', 'desc' => 'Define tone, language, and style. Every piece of content stays consistent with your brand.'],
                ['icon' => 'calendar', 'title' => 'Calendar & Insights', 'desc' => 'See your content calendar at a glance. Track performance and refine your strategy.'],
                ['icon' => 'magnifying', 'title' => 'Research & Intelligence', 'desc' => 'Product and audience research to inform your content and growth decisions.'],
            ] as $feature)
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm hover:border-indigo-200 dark:hover:border-indigo-900/50 hover:shadow-md transition-all">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                    @if($feature['icon'] === 'sparkles')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    @elseif($feature['icon'] === 'link')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    @elseif($feature['icon'] === 'clock')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif($feature['icon'] === 'microphone')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v6m4 0v-2m-4 2v-2"/></svg>
                    @elseif($feature['icon'] === 'calendar')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    @endif
                </div>
                <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-white">{{ $feature['title'] }}</h3>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 5. How it works (3 steps) --}}
<section class="py-20 lg:py-28 bg-white dark:bg-zinc-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-zinc-900 dark:text-white">
            From zero to growth in three steps
        </h2>
        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="text-center">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold text-lg shadow-md">1</span>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-white">Connect Meta</h3>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Link your Facebook Pages and Instagram in one click.</p>
            </div>
            <div class="text-center">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold text-lg shadow-md">2</span>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-white">Set your brand voice</h3>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Define tone, language, and style for your content.</p>
            </div>
            <div class="text-center">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold text-lg shadow-md">3</span>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-white">Enable autopilot</h3>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Choose posts per day and timezone. AI handles the rest.</p>
            </div>
        </div>
        <div class="mt-12 text-center">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white shadow-md hover:bg-indigo-700 transition active:scale-[0.98]">Start your mission</a>
        </div>
    </div>
</section>

{{-- 6. Testimonials (3 cards) --}}
<section class="py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-950">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-zinc-900 dark:text-white">
            What creators are saying
        </h2>
        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['quote' => 'AdsyCraft cut my content creation time by 80%. The AI actually gets my brand voice.', 'name' => 'Sarah Chen', 'role' => 'Founder, Growth Labs'],
                ['quote' => 'Finally, one place for Meta and AI. No more juggling five different tools.', 'name' => 'Marcus Webb', 'role' => 'Social Media Manager'],
                ['quote' => 'Autopilot runs while I sleep. My engagement has never been higher.', 'name' => 'Elena Rodriguez', 'role' => 'Content Creator'],
            ] as $t)
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                <p class="text-zinc-600 dark:text-zinc-400 italic">"{{ $t['quote'] }}"</p>
                <p class="mt-4 font-medium text-zinc-900 dark:text-white">{{ $t['name'] }}</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-500">{{ $t['role'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 7. Pricing teaser --}}
<section class="py-20 lg:py-28 bg-white dark:bg-zinc-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-zinc-900 dark:text-white">
            Plans that scale with you
        </h2>
        <p class="mt-4 text-lg text-center text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
            Starter, Growth, and Scale—with annual discounts. Cancel anytime.
        </p>
        <div class="mt-12 text-center">
            <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white shadow-md hover:bg-indigo-700 transition active:scale-[0.98]">View pricing</a>
        </div>
    </div>
</section>

{{-- 8. FAQ accordion (4 items) --}}
<section class="py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-950">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-zinc-900 dark:text-white">
            Frequently asked questions
        </h2>
        <div class="mt-12 space-y-4">
            @foreach([
                ['q' => 'What is AdsyCraft?', 'a' => 'AdsyCraft is a Growth Operating System for social media. It combines AI content generation, Meta connectivity, and autopilot scheduling into one dashboard.'],
                ['q' => 'Which AI providers are supported?', 'a' => 'AdsyCraft supports OpenAI, Google Gemini, and Grok. You can connect one or more providers and choose a primary for content generation.'],
                ['q' => 'How does autopilot work?', 'a' => 'Autopilot uses your brand voice and niche to generate content on a schedule you define. Set posts per day and timezone, and AdsyCraft handles the rest.'],
                ['q' => 'Can I cancel anytime?', 'a' => 'Yes. You can cancel your subscription at any time. There are no long-term contracts or lock-in periods.'],
            ] as $faq)
            <details class="group rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
                <summary class="flex items-center justify-between cursor-pointer list-none px-6 py-4 text-left font-medium text-zinc-900 dark:text-white hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                    {{ $faq['q'] }}
                    <span class="shrink-0 ml-2 transition group-open:rotate-180">
                        <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </summary>
                <div class="px-6 pb-4 text-zinc-600 dark:text-zinc-400">
                    {{ $faq['a'] }}
                </div>
            </details>
            @endforeach
        </div>
    </div>
</section>

{{-- 9. Final CTA band --}}
<section class="py-20 lg:py-28 bg-indigo-600 dark:bg-indigo-700">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-white">
            Ready to take control?
        </h2>
        <p class="mt-4 text-lg text-indigo-100">
            Free to start. No lock-in. Launch your Mission Control in minutes.
        </p>
        <div class="mt-8">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3.5 text-base font-semibold text-indigo-600 shadow-md hover:bg-indigo-50 transition active:scale-[0.98]">
                Launch Mission Control
            </a>
        </div>
    </div>
</section>
@endsection
