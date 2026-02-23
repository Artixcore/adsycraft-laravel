@extends('layouts.app')

@push('vite')
    @vite(['resources/js/onboarding.js'])
@endpush

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4 sm:px-6">
    {{-- Progress --}}
    <div class="mb-12">
        <div class="flex items-center justify-between">
            @foreach([1, 2, 3, 4, 5] as $step)
            <div class="flex items-center {{ $step < 5 ? 'flex-1' : '' }}">
                <div class="onboarding-step flex items-center justify-center w-10 h-10 rounded-full border-2 text-sm font-medium transition {{ $step === 1 ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-zinc-300 dark:border-[#3E3E3A] text-zinc-500 dark:text-zinc-400' }}" data-step="{{ $step }}">
                    {{ $step }}
                </div>
                @if($step < 5)
                <div class="flex-1 h-0.5 mx-1 bg-zinc-200 dark:bg-[#27272A]"></div>
                @endif
            </div>
            @endforeach
        </div>
        <div class="flex justify-between mt-2 text-xs text-zinc-500 dark:text-zinc-400">
            <span>Welcome</span>
            <span>Business</span>
            <span>Meta</span>
            <span>AI</span>
            <span>Done</span>
        </div>
    </div>

    {{-- Step 1: Welcome --}}
    <div id="step-1" class="onboarding-panel">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Let's set up your Mission Control</h1>
        <p class="mt-4 text-zinc-600 dark:text-zinc-400">
            We'll walk you through connecting Meta, adding AI, and creating your first business. You can skip optional steps and complete them later.
        </p>
        <div class="mt-8">
            <button type="button" id="btn-step-1-next" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                Get started
            </button>
        </div>
    </div>

    {{-- Step 2: Create business --}}
    <div id="step-2" class="onboarding-panel hidden">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Create your first business</h1>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">
            Give your business a name and configure the basics. You can add more later.
        </p>
        <form id="onboarding-business-form" class="mt-8 space-y-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Name *</label>
                <input type="text" name="name" required class="w-full rounded-lg border border-zinc-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="My Brand">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Niche</label>
                <input type="text" name="niche" class="w-full rounded-lg border border-zinc-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="e.g. Fitness, Fashion">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Posts per day</label>
                    <input type="number" name="posts_per_day" min="1" max="20" value="1" class="w-full rounded-lg border border-zinc-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Timezone *</label>
                    <input type="text" name="timezone" required value="UTC" class="w-full rounded-lg border border-zinc-300 dark:border-[#3E3E3A] dark:bg-[#161615] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="America/New_York">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="autopilot_enabled" id="onboarding_autopilot" value="1" checked class="rounded border-zinc-300 dark:border-[#3E3E3A] text-indigo-600 focus:ring-indigo-500">
                <label for="onboarding_autopilot" class="text-sm text-zinc-700 dark:text-zinc-300">Enable autopilot</label>
            </div>
            <p id="onboarding-create-message" class="text-sm text-red-600 hidden"></p>
            <div class="flex gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                    Create & continue
                </button>
                <button type="button" id="btn-step-2-skip" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 dark:border-[#3E3E3A] px-6 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-[#1a1a1a] transition">
                    Skip for now
                </button>
            </div>
        </form>
    </div>

    {{-- Step 3: Connect Meta --}}
    <div id="step-3" class="onboarding-panel hidden">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Connect Meta</h1>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">
            Link your Facebook Pages and Instagram to publish content. You can do this later from Connectors.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row gap-4">
            <a id="btn-connect-meta" href="{{ route('dashboard.connectors') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                Connect Meta
            </a>
            <button type="button" id="btn-step-3-skip" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 dark:border-[#3E3E3A] px-6 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-[#1a1a1a] transition">
                Skip for now
            </button>
        </div>
    </div>

    {{-- Step 4: Add AI --}}
    <div id="step-4" class="onboarding-panel hidden">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Add AI provider</h1>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">
            Connect OpenAI, Gemini, or Grok to generate content. You can add this later from Connectors.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row gap-4">
            <a id="btn-add-ai" href="{{ route('dashboard.connectors') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                Add AI provider
            </a>
            <button type="button" id="btn-step-4-skip" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 dark:border-[#3E3E3A] px-6 py-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-[#1a1a1a] transition">
                Skip for now
            </button>
        </div>
    </div>

    {{-- Step 5: Done --}}
    <div id="step-5" class="onboarding-panel hidden text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 dark:bg-green-950/50 text-green-600 dark:text-green-400 mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        </div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">You're ready</h1>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">
            Your Mission Control is set up. Start managing your content from the dashboard.
        </p>
        <div class="mt-8">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                Go to Mission Control
            </a>
        </div>
    </div>
</div>
@endsection
