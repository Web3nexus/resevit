@extends('layouts.landing')

@section('title', 'Pricing - Resevit')

@section('content')
    <!-- Hero Section -->
    <section class="py-20 bg-brand-primary text-white text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-6 tracking-tight">Plans for Every Restaurant</h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
                From cozy cafes to massive chains, Resevit scales with your ambition. Choose the right plan and start
                growing today.
            </p>
        </div>
    </section>

    <!-- Pricing Grid -->
    <section class="py-24 bg-brand-offwhite relative -mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                    <div
                        class="flex flex-col p-8 rounded-3xl @if($plan->is_featured) bg-brand-primary text-white ring-4 ring-brand-accent/30 shadow-2xl scale-105 z-10 @else bg-white border border-slate-200 shadow-xl @endif transition-all duration-300">
                        <div class="mb-8">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-2xl font-bold">{{ $plan->name }}</h3>
                                @if($plan->is_featured)
                                    <span
                                        class="bg-brand-accent text-brand-primary text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded">Popular</span>
                                @endif
                            </div>
                            <p class="@if($plan->is_featured) text-slate-400 @else text-slate-500 @endif text-sm">
                                {{ $plan->description }}
                            </p>
                        </div>

                        <div class="mb-8">
                            <span class="text-5xl font-extrabold tracking-tight">${{ $plan->price_monthly }}</span>
                            <span class="@if($plan->is_featured) text-slate-400 @else text-slate-500 @endif">/month</span>
                        </div>

                        <ul class="space-y-4 mb-10 flex-grow">
                            @foreach($plan->features->where('pivot.is_included', true) as $feature)
                                <li class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 @if($plan->is_featured) text-brand-accent @else text-brand-primary @endif flex-shrink-0"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm">
                                        {{ $feature->pivot->value ? $feature->pivot->value . ' ' : '' }}{{ $feature->name }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ $plan->cta_url ?? route('register') }}"
                            class="w-full inline-flex justify-center items-center px-6 py-4 rounded-xl font-bold transition-all @if($plan->is_featured) bg-brand-accent text-brand-primary hover:opacity-90 @else bg-brand-primary text-white hover:bg-brand-secondary @endif">
                            {{ $plan->cta_text }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-brand-primary mb-12 text-center tracking-tight">Pricing FAQs</h2>

            <div class="space-y-6">
                @foreach($faqs as $faq)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <button
                            class="w-full px-6 py-5 text-left flex justify-between items-center hover:bg-slate-50 transition-colors"
                            onclick="this.nextElementSibling.classList.toggle('hidden')">
                            <span class="font-bold text-brand-primary">{{ $faq->question }}</span>
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 py-5 border-t border-slate-50 hidden text-slate-600 leading-relaxed">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </div>
                @endforeach

                @if($faqs->isEmpty())
                    <div class="text-center text-slate-400 italic">No pricing FAQs added yet.</div>
                @endif
            </div>
        </div>
    </section>
@endsection