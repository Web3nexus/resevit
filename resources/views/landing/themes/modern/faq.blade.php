@extends($layout ?? 'layouts.landing-modern')

@section('title', 'Frequently Asked Questions - Resevit')

@section('content')
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden border-b border-brand-modern-border">
        <!-- Atmospheric Background -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-full -z-10">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-modern-accent/10 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="text-brand-modern-accent font-bold tracking-widest uppercase text-xs mb-4 block glow-text">
                Support & Resources
            </span>
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight">How Can We Help?</h1>
            <p class="text-lg md:text-xl text-brand-modern-muted max-w-2xl mx-auto leading-relaxed">
                Find answers to common questions about Resevit's features, pricing, and integration.
            </p>
        </div>
    </section>

    <section class="py-24 bg-brand-modern-bg">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @forelse($faqs as $category => $categoryFaqs)
                <div class="mb-20">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="h-px bg-brand-modern-border flex-grow"></div>
                        <h2 class="text-sm font-black text-white uppercase tracking-[0.3em]">
                            {{ $category ?: 'General' }}
                        </h2>
                        <div class="h-px bg-brand-modern-border flex-grow"></div>
                    </div>

                    <div class="space-y-4" x-data="{ activeFaq: null }">
                        @foreach($categoryFaqs as $index => $faq)
                            <div class="gradient-border overflow-hidden transition-all duration-300"
                                :class="{ 'ring-1 ring-brand-modern-accent/30 shadow-[0_0_30px_rgba(125,64,255,0.1)]': activeFaq === {{ $index }} }">
                                <button class="w-full px-8 py-6 text-left flex justify-between items-center transition-colors group"
                                    @click="activeFaq = activeFaq === {{ $index }} ? null : {{ $index }}">
                                    <span
                                        class="font-bold text-lg text-white group-hover:text-brand-modern-accent transition-colors leading-tight">{{ $faq->question }}</span>
                                    <div class="ml-4 w-8 h-8 rounded-full bg-white/5 flex items-center justify-center transition-transform duration-300"
                                        :class="{ 'rotate-180 bg-brand-modern-accent/20 text-brand-modern-accent': activeFaq === {{ $index }}, 'text-brand-modern-muted': activeFaq !== {{ $index }} }">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </button>
                                <div x-show="activeFaq === {{ $index }}" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="px-8 pb-6 text-brand-modern-muted leading-relaxed text-base">
                                    <div class="pt-2 border-t border-white/5">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-brand-modern-card border border-brand-modern-border rounded-3xl">
                    <div
                        class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6 border border-white/10">
                        <i class="fa-solid fa-circle-question text-3xl text-brand-modern-muted"></i>
                    </div>
                    <p class="text-brand-modern-muted text-lg">We're still building our FAQ. Please contact us if you have any
                        questions!
                    </p>
                    <a href="{{ route('contact') }}"
                        class="mt-8 inline-flex items-center gap-2 px-8 py-3 bg-brand-modern-accent text-white font-bold rounded-full hover:bg-opacity-80 transition-all shadow-[0_0_20px_rgba(125,64,255,0.2)]">
                        Contact Support
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Still Have Questions? -->
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-brand-modern-accent/5 to-transparent"></div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-4xl font-black text-white mb-6">Still have questions?</h2>
            <p class="text-brand-modern-muted text-lg mb-10 max-w-xl mx-auto">If you can't find the answer you're looking
                for, please feel free to reach out to our specialist team.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('contact') }}"
                    class="inline-flex items-center justify-center px-10 py-4 bg-white text-brand-modern-bg font-black rounded-full hover:bg-brand-modern-accent hover:text-white transition-all">
                    Reach Out Today
                </a>
                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center px-10 py-4 bg-white/5 border border-white/10 text-white font-black rounded-full hover:bg-white/10 transition-all">
                    Join Resevit
                </a>
            </div>
        </div>
    </section>
@endsection