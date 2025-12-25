@extends('layouts.landing')

@section('title', 'Frequently Asked Questions - Resevit')

@section('content')
    <section class="py-20 bg-brand-primary text-white text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-6 tracking-tight">How Can We Help?</h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Find answers to common questions about Resevit's features, pricing, and integration.
            </p>
        </div>
    </section>

    <section class="py-24 bg-brand-offwhite">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @forelse($faqs as $category => $categoryFaqs)
                <div class="mb-16">
                    <h2
                        class="text-2xl font-bold text-brand-primary mb-8 border-l-4 border-brand-accent pl-4 uppercase tracking-wider">
                        {{ $category ?: 'General' }}
                    </h2>

                    <div class="space-y-4">
                        @foreach($categoryFaqs as $faq)
                            <div class="bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
                                <button
                                    class="w-full px-6 py-5 text-left flex justify-between items-center hover:bg-slate-100 transition-colors group"
                                    onclick="this.nextElementSibling.classList.toggle('hidden')">
                                    <span class="font-bold text-brand-primary">{{ $faq->question }}</span>
                                    <svg class="w-5 h-5 text-slate-400 group-hover:text-brand-accent transition-colors" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                        </path>
                                    </svg>
                                </button>
                                <div class="px-6 py-5 border-t border-slate-100 hidden bg-white text-slate-600 leading-relaxed">
                                    {!! nl2br(e($faq->answer)) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-20">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-slate-500 text-lg">We're still building our FAQ. Please contact us if you have any questions!
                    </p>
                    <a href="{{ route('contact') }}"
                        class="mt-6 inline-flex text-brand-primary font-bold hover:text-brand-accent">
                        Go to Contact Page &rarr;
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Still Have Questions? -->
    <section class="py-24 bg-brand-primary text-white text-center">
        <div class="max-w-3xl mx-auto px-4">
            <h2 class="text-3xl font-bold mb-6">Still have questions?</h2>
            <p class="text-slate-400 mb-10">If you can't find the answer you're looking for, please feel free to reach out
                to our support team.</p>
            <a href="{{ route('contact') }}"
                class="inline-flex items-center justify-center px-10 py-4 bg-brand-accent text-brand-primary font-bold rounded-xl hover:scale-105 transition-transform">
                Contact Support
            </a>
        </div>
    </section>
@endsection