<section class="py-24 bg-brand-offwhite" id="pricing">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <h2 class="text-4xl font-extrabold text-brand-primary mb-6 tracking-tight">Simple, Transparent Pricing</h2>
            <p class="text-lg text-slate-600">Choose the plan that's right for your business. No hidden fees.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($plans as $plan)
                <div
                    class="flex flex-col p-8 rounded-3xl @if($plan->is_featured) bg-brand-primary text-white ring-4 ring-brand-accent/30 shadow-2xl scale-105 z-10 @else bg-slate-50 border border-slate-100 @endif transition-all duration-300">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-2">{{ $plan->name }}</h3>
                        <p class="@if($plan->is_featured) text-slate-400 @else text-slate-500 @endif text-sm">
                            {{ $plan->description }}
                        </p>
                    </div>

                    <div class="mb-8">
                        <span class="text-5xl font-extrabold tracking-tight">${{ $plan->price_monthly }}</span>
                        <span class="@if($plan->is_featured) text-slate-400 @else text-slate-500 @endif">/month</span>
                    </div>

                    <ul class="space-y-4 mb-10 flex-grow">
                        @foreach($plan->features as $feature)
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