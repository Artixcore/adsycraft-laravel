@extends('layouts.landing')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
    <div class="text-center mb-16">
        <h1 class="text-4xl sm:text-5xl font-bold text-zinc-900 dark:text-white">
            Plans that scale with you
        </h1>
        <p class="mt-6 text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
            Cancel anytime · No credit card for trial
        </p>
        {{-- Billing toggle --}}
        <div class="mt-8 inline-flex items-center gap-2 rounded-lg border border-zinc-200 dark:border-[#27272A] p-1 bg-zinc-100 dark:bg-[#1a1a1a]">
            <button type="button" id="billing-monthly" class="billing-toggle px-4 py-2 rounded-md text-sm font-medium text-zinc-900 dark:text-white bg-white dark:bg-[#27272A] shadow-sm transition">
                Monthly
            </button>
            <button type="button" id="billing-yearly" class="billing-toggle px-4 py-2 rounded-md text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition">
                Yearly <span class="text-xs text-green-600 dark:text-green-400">Save 20%</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Starter --}}
        <div class="rounded-2xl border border-zinc-200 dark:border-[#27272A] bg-white dark:bg-[#161616] p-8 flex flex-col">
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">Starter</h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">For solopreneurs</p>
            <div class="mt-6">
                <span class="price-monthly text-4xl font-bold text-zinc-900 dark:text-white">$29</span>
                <span class="price-yearly text-4xl font-bold text-zinc-900 dark:text-white hidden">$23</span>
                <span class="text-zinc-600 dark:text-zinc-400">/ month</span>
            </div>
            <ul class="mt-8 space-y-4 flex-1">
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> AI Content Engine</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> 1 Meta account</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> Autopilot</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> 1 Brand Voice</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> 30 posts/month</li>
            </ul>
            <a href="{{ route('register') }}" class="mt-8 w-full inline-flex items-center justify-center rounded-lg border border-zinc-300 dark:border-[#27272A] px-6 py-3.5 text-base font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-[#1a1a1a] transition">
                Get started
            </a>
        </div>

        {{-- Growth (recommended) --}}
        <div class="rounded-2xl border-2 border-indigo-500 dark:border-indigo-500 bg-white dark:bg-[#161616] p-8 flex flex-col relative">
            <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-indigo-600 text-white text-xs font-medium">Most popular</span>
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">Growth</h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">For scaling brands</p>
            <div class="mt-6">
                <span class="price-monthly text-4xl font-bold text-zinc-900 dark:text-white">$79</span>
                <span class="price-yearly text-4xl font-bold text-zinc-900 dark:text-white hidden">$63</span>
                <span class="text-zinc-600 dark:text-zinc-400">/ month</span>
            </div>
            <ul class="mt-8 space-y-4 flex-1">
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> AI Content Engine</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> 3 Meta accounts</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> Autopilot</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> 3 Brand Voices</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> 150 posts/month</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> Research</li>
            </ul>
            <a href="{{ route('register') }}" class="mt-8 w-full inline-flex items-center justify-center rounded-lg bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white hover:bg-indigo-700 transition">
                Get started
            </a>
        </div>

        {{-- Scale --}}
        <div class="rounded-2xl border border-zinc-200 dark:border-[#27272A] bg-white dark:bg-[#161616] p-8 flex flex-col">
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">Scale</h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">For teams & agencies</p>
            <div class="mt-6">
                <span class="price-monthly text-4xl font-bold text-zinc-900 dark:text-white">$199</span>
                <span class="price-yearly text-4xl font-bold text-zinc-900 dark:text-white hidden">$159</span>
                <span class="text-zinc-600 dark:text-zinc-400">/ month</span>
            </div>
            <ul class="mt-8 space-y-4 flex-1">
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> AI Content Engine</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> Unlimited Meta accounts</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> Autopilot</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> 10 Brand Voices</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> 500 posts/month</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> Research</li>
                <li class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"><span class="text-indigo-600 dark:text-indigo-400">✓</span> Priority support</li>
            </ul>
            <a href="{{ route('register') }}" class="mt-8 w-full inline-flex items-center justify-center rounded-lg border border-zinc-300 dark:border-[#27272A] px-6 py-3.5 text-base font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-[#1a1a1a] transition">
                Get started
            </a>
        </div>
    </div>

    <p class="mt-12 text-center text-sm text-zinc-500 dark:text-zinc-500">
        All plans include a free trial. Cancel anytime. Secure checkout.
    </p>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthly = document.getElementById('billing-monthly');
    const yearly = document.getElementById('billing-yearly');
    const monthlyPrices = document.querySelectorAll('.price-monthly');
    const yearlyPrices = document.querySelectorAll('.price-yearly');

    function setBilling(isYearly) {
        if (isYearly) {
            monthly.classList.remove('bg-white', 'dark:bg-[#27272A]', 'shadow-sm', 'text-zinc-900', 'dark:text-white');
            monthly.classList.add('text-zinc-600', 'dark:text-zinc-400');
            yearly.classList.add('bg-white', 'dark:bg-[#27272A]', 'shadow-sm', 'text-zinc-900', 'dark:text-white');
            yearly.classList.remove('text-zinc-600', 'dark:text-zinc-400');
            monthlyPrices.forEach(el => el.classList.add('hidden'));
            yearlyPrices.forEach(el => el.classList.remove('hidden'));
        } else {
            yearly.classList.remove('bg-white', 'dark:bg-[#27272A]', 'shadow-sm', 'text-zinc-900', 'dark:text-white');
            yearly.classList.add('text-zinc-600', 'dark:text-zinc-400');
            monthly.classList.add('bg-white', 'dark:bg-[#27272A]', 'shadow-sm', 'text-zinc-900', 'dark:text-white');
            monthly.classList.remove('text-zinc-600', 'dark:text-zinc-400');
            yearlyPrices.forEach(el => el.classList.add('hidden'));
            monthlyPrices.forEach(el => el.classList.remove('hidden'));
        }
    }

    monthly.addEventListener('click', () => setBilling(false));
    yearly.addEventListener('click', () => setBilling(true));
});
</script>
@endpush
@endsection
