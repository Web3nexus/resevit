<section class="py-24 relative overflow-hidden" id="pricing">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight">Simple, Efficient Pricing</h2>
            <p class="text-lg text-brand-modern-muted leading-relaxed">Choose the plan that's right for your business.
                No hidden fees, just pure performance.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
            @foreach($plans as $plan)
                <div class="relative group">
                    @if($plan->is_featured)
                        <div
                            class="absolute -inset-1 bg-linear-to-r from-brand-modern-accent to-brand-modern-secondary rounded-3xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
                        </div>
                    @endif

                    <div
                        class="relative flex flex-col p-8 rounded-3xl bg-brand-modern-card border border-brand-modern-border hover:border-white/20 transition-all duration-300 @if($plan->is_featured) scale-105 shadow-2xl z-10 @endif">
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-bold">{{ $plan->name }}</h3>
                                @if($plan->is_featured)
                                    <span
                                        class="px-3 py-1 rounded-full bg-brand-modern-accent/10 border border-brand-modern-accent/20 text-brand-modern-accent text-[10px] font-black uppercase tracking-widest">Most
                                        Advanced</span>
                                @endif
                            </div>
                            <p class="text-brand-modern-muted text-sm leading-relaxed">
                                {{ $plan->description }}
                            </p>
                        </div>

                        <div class="mb-8 flex items-baseline gap-1">
                            <span class="text-5xl font-black text-white">${{ $plan->price_monthly }}</span>
                            <span class="text-brand-modern-muted text-sm uppercase tracking-wider font-bold">/mo</span>
                        </div>

                        <div class="space-y-4 mb-10 grow">
                            <p class="text-[10px] font-bold text-brand-modern-muted uppercase tracking-widest mb-4">What's
                                included:</p>
                            @foreach($plan->features->where('pivot.is_included', true) as $feature)
                                <div class="flex items-start gap-3">
                                    <div
                                        class="mt-1 w-5 h-5 rounded-full @if($plan->is_featured) bg-brand-modern-accent @else bg-white/10 @endif flex items-center justify-center shrink-0">
                                        <i
                                            class="fa-solid fa-check text-[10px] @if($plan->is_featured) text-white @else text-brand-modern-text @endif"></i>
                                    </div>
                                    <span class="text-sm text-brand-modern-text">
                                        {{ $feature->pivot->value ? $feature->pivot->value . ' ' : '' }}{{ $feature->name }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <a href="{{ $plan->cta_url ?? route('register') }}"
                            class="w-full inline-flex justify-center items-center px-6 py-4 rounded-xl font-bold text-white transition-all @if($plan->is_featured) bg-brand-modern-accent shadow-[0_0_20px_rgba(125,64,255,0.3)] hover:bg-opacity-90 @else bg-white/5 border border-white/10 hover:bg-white/10 @endif">
                            {{ $plan->cta_text }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-20 text-center">
            <p class="text-brand-modern-muted text-sm">Need a custom plan for 50+ locations? <a href="#"
                    class="text-white hover:text-brand-modern-accent font-bold underline decoration-brand-modern-accent/50">Contact
                    our Enterprise team</a></p>
        </div>
    </div>
</section>