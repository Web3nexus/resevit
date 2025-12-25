<section class="py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative bg-brand-primary rounded-[3rem] overflow-hidden p-12 md:p-24 text-center">
            <!-- Background Decoration -->
            <div class="absolute top-0 left-0 w-64 h-64 bg-brand-accent/10 rounded-full blur-3xl -ml-32 -mt-32"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-brand-secondary/20 rounded-full blur-3xl -mr-48 -mb-48">
            </div>

            <div class="relative z-10 max-w-2xl mx-auto">
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-8 tracking-tight">
                    {{ $section->title ?? 'Ready to Transform Your Restaurant?' }}
                </h2>
                <p class="text-xl text-slate-300 mb-12 leading-relaxed">
                    {{ $section->content['description'] ?? 'Join thousands of satisfied restaurant owners who have optimized their operations with Resevit.' }}
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center px-10 py-5 bg-brand-accent text-brand-primary font-extrabold rounded-2xl hover:scale-105 transition-transform shadow-2xl shadow-brand-accent/30">
                        Get Started for Free
                    </a>
                    <a href="{{ route('contact') }}"
                        class="inline-flex items-center justify-center px-10 py-5 bg-white/10 text-white font-extrabold rounded-2xl hover:bg-white/20 transition-all border border-white/10">
                        Talk to an Expert
                    </a>
                </div>

                <p class="mt-8 text-slate-500 text-sm font-medium">
                    No credit card required • 14-day free trial • Cancel anytime
                </p>
            </div>
        </div>
    </div>
</section>