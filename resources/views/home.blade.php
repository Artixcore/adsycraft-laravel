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
                AI-powered content, Meta sync, and autopilot scheduling. One platform. Zero guesswork. Total growth control.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition active:scale-[0.98]">
                    Start Free
                </a>
                <a href="{{ route('home') }}#how-it-works" class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl border border-zinc-300 dark:border-zinc-600 px-6 py-3.5 text-base font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition active:scale-[0.98]">
                    See Demo
                </a>
            </div>
            <ul class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8 text-sm text-zinc-600 dark:text-zinc-400">
                <li class="flex items-center gap-2">
                    <span class="text-indigo-600 dark:text-indigo-400">✓</span>
                    AI generates on-brand content—set your voice once
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-indigo-600 dark:text-indigo-400">✓</span>
                    Auto-publish to Facebook & Instagram—no manual posting
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-indigo-600 dark:text-indigo-400">✓</span>
                    Research competitor ads & track insights—all in one dashboard
                </li>
            </ul>
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

{{-- 3. Problem --}}
<section class="py-20 lg:py-28 bg-white dark:bg-zinc-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-zinc-900 dark:text-white">
            The chaos of social growth—and why most brands stay stuck
        </h2>
        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 p-6">
                <p class="font-semibold text-zinc-900 dark:text-white mb-2">Posting manually</p>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Hours lost every week copying, pasting, and hitting publish. Your time belongs to strategy, not busywork.</p>
            </div>
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 p-6">
                <p class="font-semibold text-zinc-900 dark:text-white mb-2">Inconsistent content</p>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Voice drift, off-brand posts, and the sinking feeling that your feed doesn't reflect who you are.</p>
            </div>
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 p-6">
                <p class="font-semibold text-zinc-900 dark:text-white mb-2">Guessing what ads work</p>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Stumbling in the dark while competitors run proven creatives. Research tools show you data—but who builds the campaigns?</p>
            </div>
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 p-6">
                <p class="font-semibold text-zinc-900 dark:text-white mb-2">Switching between tools</p>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Scheduler here, AI there, ad library somewhere else. Context switching kills momentum.</p>
            </div>
        </div>
        <p class="mt-10 text-center text-lg font-medium text-zinc-700 dark:text-zinc-300">
            Most tools give you one piece. You need the whole system.
        </p>
    </div>
</section>

{{-- 4. Solution --}}
<section class="py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-950">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-zinc-900 dark:text-white">
            One Platform. Total Growth Control.
        </h2>
        <p class="mt-6 text-lg text-center text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
            AdsyCraft brings research, creation, publishing, and optimization into one command center. No more guessing. No more juggling.
        </p>
        <div class="mt-12 max-w-3xl mx-auto space-y-4">
            <div class="flex gap-4 items-start rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
                <span class="shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-sm font-bold">1</span>
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-white">Generates AI content</p>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Set your brand voice once. AI creates on-brand posts with OpenAI, Gemini, or Grok.</p>
                </div>
            </div>
            <div class="flex gap-4 items-start rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
                <span class="shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-sm font-bold">2</span>
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-white">Schedules & auto-publishes</p>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Choose posts per day and timezone. Content goes live to Facebook and Instagram automatically.</p>
                </div>
            </div>
            <div class="flex gap-4 items-start rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
                <span class="shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-sm font-bold">3</span>
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-white">Researches competitor ads</p>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Built-in Meta Ads Library. See what's working before you spend a dollar.</p>
                </div>
            </div>
            <div class="flex gap-4 items-start rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
                <span class="shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-sm font-bold">4</span>
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-white">Prepares ads campaigns</p>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Campaign-ready ads engine. Turn research into launch-ready creatives.</p>
                </div>
            </div>
            <div class="flex gap-4 items-start rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-5">
                <span class="shrink-0 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-sm font-bold">5</span>
                <div>
                    <p class="font-semibold text-zinc-900 dark:text-white">Tracks insights</p>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Page insights and analytics. Optimize based on real performance, not hunches.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 5. Feature blocks (8 benefit-driven) --}}
<section class="py-20 lg:py-28 bg-white dark:bg-zinc-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-zinc-900 dark:text-white">
            Everything you need to scale
        </h2>
        <p class="mt-4 text-lg text-center text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
            From AI content to Meta sync—all in one place.
        </p>
        <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'sparkles', 'title' => 'AI Content Autopilot', 'headline' => 'Content that sounds like you—without the grind', 'desc' => 'AI generates posts in your voice. Set tone, language, and style once. Choose OpenAI, Gemini, or Grok.', 'proof' => '80% less time on content creation'],
                ['icon' => 'calendar', 'title' => 'Smart Content Calendar', 'headline' => 'See your entire content strategy at a glance', 'desc' => 'Plan, preview, and adjust posts before they go live. Drag, drop, and optimize your feed.', 'proof' => 'Never miss a post again'],
                ['icon' => 'link', 'title' => 'One-Click Meta Publishing', 'headline' => 'Facebook & Instagram from one dashboard', 'desc' => 'Connect Pages and accounts. Publish and schedule across both platforms—no switching apps.', 'proof' => 'One connection, two platforms'],
                ['icon' => 'magnifying', 'title' => 'Built-in Ad Intelligence', 'headline' => 'Know what works before you spend', 'desc' => 'Meta Ads Library research built in. See competitor creatives, angles, and formats.', 'proof' => 'Research without leaving the platform'],
                ['icon' => 'rocket', 'title' => 'Campaign-Ready Ads Engine', 'headline' => 'Turn insights into launch-ready ads', 'desc' => 'Scaffold ads from your research. Meta Marketing API integration. Go from idea to campaign faster.', 'proof' => 'From research to launch in minutes'],
                ['icon' => 'microphone', 'title' => 'Brand Voice Control', 'headline' => 'One voice. Every post. Every time.', 'desc' => 'Define tone, language, and style. AI stays consistent across all content.', 'proof' => 'Zero voice drift'],
                ['icon' => 'building', 'title' => 'Workspace & Agency Ready', 'headline' => 'Manage multiple brands from one place', 'desc' => 'Workspaces for clients or brands. Role-based access. Built for solopreneurs and agencies alike.', 'proof' => 'One login, unlimited brands'],
                ['icon' => 'chart', 'title' => 'Insight-Driven Optimization', 'headline' => 'Optimize based on data, not guesswork', 'desc' => 'Page insights, engagement metrics, and performance tracking. Refine your strategy with real numbers.', 'proof' => 'Data-backed decisions'],
            ] as $feature)
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 p-6 shadow-sm hover:border-indigo-200 dark:hover:border-indigo-900/50 hover:shadow-md transition-all">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                    @if($feature['icon'] === 'sparkles')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    @elseif($feature['icon'] === 'link')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    @elseif($feature['icon'] === 'microphone')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v6m4 0v-2m-4 2v-2"/></svg>
                    @elseif($feature['icon'] === 'calendar')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    @elseif($feature['icon'] === 'magnifying')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    @elseif($feature['icon'] === 'rocket')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    @elseif($feature['icon'] === 'building')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    @endif
                </div>
                <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-white">{{ $feature['title'] }}</h3>
                <p class="mt-1 text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $feature['headline'] }}</p>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ $feature['desc'] }}</p>
                <p class="mt-3 text-xs font-medium text-indigo-600 dark:text-indigo-400">"{{ $feature['proof'] }}"</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 6. How it works (4 steps) --}}
<section id="how-it-works" class="py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-950 scroll-mt-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-zinc-900 dark:text-white">
            From zero to growth in four steps
        </h2>
        <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <div class="text-center">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold text-lg shadow-md">1</span>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-white">Create workspace</h3>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Set up your brand or client. Define your niche and goals.</p>
            </div>
            <div class="text-center">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold text-lg shadow-md">2</span>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-white">Connect Meta</h3>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Link Facebook Pages and Instagram in one click. Secure OAuth—no scraping.</p>
            </div>
            <div class="text-center">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold text-lg shadow-md">3</span>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-white">Generate & schedule content</h3>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Set your brand voice. Choose posts per day. AI creates and schedules. Autopilot runs.</p>
            </div>
            <div class="text-center">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold text-lg shadow-md">4</span>
                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-white">Optimize & scale with insights</h3>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Track performance. Research competitor ads. Refine and scale what works.</p>
            </div>
        </div>
        <div class="mt-12 text-center">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white shadow-md hover:bg-indigo-700 transition active:scale-[0.98]">Start Free</a>
        </div>
    </div>
</section>

{{-- 7. Competitive Differentiation --}}
<section class="py-20 lg:py-28 bg-white dark:bg-zinc-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-zinc-900 dark:text-white">
            Not another research tool. Not another scheduler. The full system.
        </h2>
        <p class="mt-6 text-lg text-center text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
            Research-only tools show you what's working—but who builds the content? Manual schedulers save time—but who creates it? Ad managers optimize—but who researches? AdsyCraft does all four.
        </p>
        <p class="mt-8 text-center text-xl font-semibold text-indigo-600 dark:text-indigo-400">
            Research + Creation + Publishing + Optimization — All-in-One
        </p>
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 p-5 text-center">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-500">Research-only tools</p>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">See data, then leave to build elsewhere</p>
            </div>
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 p-5 text-center">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-500">Manual schedulers</p>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Post what you create—somewhere else</p>
            </div>
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 p-5 text-center">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-500">Ad managers</p>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Optimize campaigns—content comes from elsewhere</p>
            </div>
        </div>
        <div class="mt-10 rounded-2xl border-2 border-indigo-500 dark:border-indigo-500 bg-indigo-50/50 dark:bg-indigo-950/20 p-6 text-center max-w-xl mx-auto">
            <p class="font-semibold text-zinc-900 dark:text-white">AdsyCraft</p>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">One platform. End to end.</p>
        </div>
    </div>
</section>

{{-- 8. Social Proof (testimonials + trust) --}}
<section class="py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-950">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-zinc-900 dark:text-white">
            Trusted by founders, marketers, and creators
        </h2>
        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['quote' => 'AdsyCraft cut my content creation time by 80%. The AI actually gets my brand voice—and the ad research means I\'m not guessing anymore.', 'name' => 'Sarah Chen', 'role' => 'Founder, Growth Labs'],
                ['quote' => 'Finally, one place for Meta and AI. No more juggling five different tools. Autopilot runs while I focus on clients.', 'name' => 'Marcus Webb', 'role' => 'Social Media Manager'],
                ['quote' => 'The ad library research plus campaign scaffold changed how we launch. We see what works, then build it—all in one dashboard.', 'name' => 'Elena Rodriguez', 'role' => 'Agency Owner'],
            ] as $t)
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                <p class="text-zinc-600 dark:text-zinc-400 italic">"{{ $t['quote'] }}"</p>
                <p class="mt-4 font-medium text-zinc-900 dark:text-white">{{ $t['name'] }}</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-500">{{ $t['role'] }}</p>
            </div>
            @endforeach
        </div>
        <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-6 sm:gap-10 text-sm text-zinc-600 dark:text-zinc-400">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Secure OAuth—official Meta APIs, no scraping
            </span>
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                No data sold. Your content stays yours.
            </span>
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 0v-5h-.581m0 0a8.003 8.003 0 01-15.357 2m15.357 2H15"/></svg>
                Cancel anytime. No lock-in.
            </span>
        </div>
    </div>
</section>

{{-- 9. Pricing teaser --}}
<section class="py-20 lg:py-28 bg-white dark:bg-zinc-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl sm:text-4xl font-bold text-center text-zinc-900 dark:text-white">
            Plans that scale with you
        </h2>
        <p class="mt-4 text-lg text-center text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
            Outcome-focused, not feature checklists. Start free. Grow when you're ready.
        </p>
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 p-6">
                <p class="font-semibold text-zinc-900 dark:text-white">Starter</p>
                <p class="mt-1 text-sm font-medium text-indigo-600 dark:text-indigo-400">Get consistent.</p>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">For solopreneurs ready to stop manual posting. AI + autopilot + 1 Meta account.</p>
            </div>
            <div class="rounded-2xl border-2 border-indigo-500 dark:border-indigo-500 bg-white dark:bg-zinc-900 p-6">
                <p class="font-semibold text-zinc-900 dark:text-white">Growth</p>
                <p class="mt-1 text-sm font-medium text-indigo-600 dark:text-indigo-400">Scale your presence.</p>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">For brands that need more. Multiple accounts, ad research, higher volume.</p>
            </div>
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 p-6">
                <p class="font-semibold text-zinc-900 dark:text-white">Pro</p>
                <p class="mt-1 text-sm font-medium text-indigo-600 dark:text-indigo-400">Run the show.</p>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">For teams and agencies. Unlimited accounts, priority support, full control.</p>
            </div>
        </div>
        <div class="mt-10 text-center">
            <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white shadow-md hover:bg-indigo-700 transition active:scale-[0.98]">View pricing</a>
        </div>
    </div>
</section>

{{-- 11. FAQ accordion (4 items) --}}
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

{{-- 12. Final CTA band --}}
<section class="py-20 lg:py-28 bg-indigo-600 dark:bg-indigo-700">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-white">
            Stop Guessing. Start Growing.
        </h2>
        <p class="mt-4 text-lg text-indigo-100">
            Free to start. No credit card. Connect Meta and launch in minutes.
        </p>
        <div class="mt-8">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3.5 text-base font-semibold text-indigo-600 shadow-md hover:bg-indigo-50 transition active:scale-[0.98]">
                Start Free
            </a>
        </div>
    </div>
</section>
@endsection
