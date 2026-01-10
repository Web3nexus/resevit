<section class="py-24 bg-brand-offwhite" id="pricing">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <h2 class="text-4xl font-extrabold text-brand-primary mb-6 tracking-tight">Simple, Transparent Pricing</h2>
            <p class="text-lg text-slate-600">Choose the plan that's right for your business. No hidden fees.</p>
        </div>

        @php
            $firstPlan = $plans->first();
            $discount = $firstPlan && $firstPlan->price_monthly > 0
                ? round((($firstPlan->price_monthly * 12) - $firstPlan->price_yearly) / ($firstPlan->price_monthly * 12) * 100)
                : 17;
        @endphp

        <div class="flex flex-col items-center mb-16" x-data="{ billingCycle: 'monthly' }">
            <!-- Refined Monthly/Yearly Toggle -->
            <div class="flex items-center space-x-4 mb-4">
                <div class="inline-flex p-1.5 bg-white border border-slate-200 rounded-2xl shadow-sm">
                    <button @click="billingCycle = 'monthly'"
                        class="px-8 py-3 text-sm font-bold rounded-xl transition-all duration-300"
                        :class="billingCycle === 'monthly' ? 'bg-brand-primary text-white shadow-lg' : 'text-slate-600 hover:text-slate-900'">
                        Monthly
                    </button>
                    <button @click="billingCycle = 'yearly'"
                        class="px-8 py-3 text-sm font-bold rounded-xl transition-all duration-300 flex items-center gap-2"
                        :class="billingCycle === 'yearly' ? 'bg-brand-primary text-white shadow-lg' : 'text-slate-600 hover:text-slate-900'">
                        Yearly
                        <span class="text-[10px] opacity-80"
                            :class="billingCycle === 'yearly' ? 'text-white' : 'text-slate-500'">(Save
                            {{ $discount }}%)</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full mt-12">
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
                            <div x-show="billingCycle === 'monthly'">
                                <span class="text-5xl font-extrabold tracking-tight">${{ $plan->price_monthly }}</span>
                                <span
                                    class="@if($plan->is_featured) text-slate-400 @else text-slate-500 @endif">/month</span>
                            </div>
                            <div x-show="billingCycle === 'yearly'" x-cloak>
                                <span class="text-5xl font-extrabold tracking-tight">${{ $plan->price_yearly }}</span>
                                <span
                                    class="@if($plan->is_featured) text-slate-400 @else text-slate-500 @endif">/year</span>
                            </div>
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

                        <a :href="'{{ route('register') }}?plan={{ $plan->id }}&cycle=' + billingCycle"
                            class="w-full inline-flex justify-center items-center px-6 py-4 rounded-xl font-bold transition-all @if($plan->is_featured) bg-brand-accent text-brand-primary hover:opacity-90 @else bg-brand-primary text-white hover:bg-brand-secondary @endif">
                            {{ $plan->cta_text }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
</section>