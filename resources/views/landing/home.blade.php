@extends('layouts.landing')

@section('content')
    @php
        $settings = \App\Models\PlatformSetting::current();
        $landing = $settings->landing_settings ?? [];
    @endphp

    {{-- Static Hero Section --}}
    <section class="relative bg-brand-primary text-white overflow-hidden py-24 sm:py-32">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-accent rounded-full blur-[120px] -mr-64 -mt-64">
            </div>
            <div
                class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-brand-secondary rounded-full blur-[100px] -ml-48 -mb-48">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-widest uppercase bg-brand-accent/20 text-brand-accent mb-6 border border-brand-accent/30">
                        {{ $landing['hero_badge'] ?? 'THE FUTURE OF DINING' }}
                    </span>
                    <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight leading-[1.1] mb-8">
                        {!! clean($landing['hero_title'] ?? 'Maximize Your Restaurant’s <span class="text-brand-accent">Potential</span>') !!}
                    </h1>
                    <p class="text-xl text-slate-300 mb-10 leading-relaxed max-w-xl">
                        {{ $landing['hero_subtitle'] ?? "Streamline reservations, optimize staff schedules, and delight customers with the world's most advanced restaurant management platform." }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ $landing['hero_cta_url'] ?? route('register') }}"
                            class="inline-flex items-center justify-center px-8 py-4 bg-brand-accent text-brand-primary font-bold rounded-xl hover:scale-105 transition-transform shadow-xl shadow-brand-accent/20">
                            {{ $landing['hero_cta_text'] ?? 'Get Started Free' }}
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        @if(!empty($landing['hero_secondary_cta_text']))
                            <a href="{{ $landing['hero_secondary_cta_url'] ?? '#' }}"
                                class="inline-flex items-center justify-center px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition-all border border-white/10">
                                {{ $landing['hero_secondary_cta_text'] }}
                            </a>
                        @else
                            <a href="#"
                                class="inline-flex items-center justify-center px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition-all border border-white/10">
                                Watch Demo
                            </a>
                        @endif
                    </div>
                    <div class="mt-12 flex items-center space-x-6 grayscale opacity-60">
                        <span class="text-sm font-medium text-slate-500 uppercase tracking-widest">Trusted By</span>
                        <div class="flex space-x-8">
                            <div class="w-8 h-8 rounded-full bg-white/20"></div>
                            <div class="w-8 h-8 rounded-full bg-white/20"></div>
                            <div class="w-8 h-8 rounded-full bg-white/20"></div>
                        </div>
                    </div>
                </div>

                <div class="relative group">
                    <div
                        class="absolute inset-0 bg-brand-accent/20 rounded-3xl blur-3xl rotate-3 transform group-hover:rotate-6 transition-transform duration-500">
                    </div>
                    <div
                        class="relative bg-brand-primary/50 backdrop-blur-sm border border-white/10 p-4 rounded-3xl shadow-2xl overflow-hidden">
                        <div
                            class="aspect-video bg-gradient-to-br from-brand-primary to-slate-900 rounded-2xl flex items-center justify-center border border-white/5">
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-brand-accent/20 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-brand-accent/30">
                                    <svg class="w-8 h-8 text-brand-accent" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-slate-400 font-medium">Interactive Platform Preview</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Dynamic Features Section --}}
    <section class="py-24 bg-brand-offwhite">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <span class="text-brand-accent font-bold tracking-widest uppercase text-sm mb-4 block">CORE
                    CAPABILITIES</span>
                <h2 class="text-4xl font-extrabold text-brand-primary mb-6 tracking-tight">Powerful Features to Control Your
                    Success</h2>
                <p class="text-lg text-slate-600 leading-relaxed">Everything you need to run your restaurant, all in one
                    intuitive platform.</p>
            </div>

            @php
                $features = \App\Models\PricingFeature::where('is_active', true)
                    ->orderBy('order')
                    ->get();
                $initialCount = 3;
            @endphp

            <div x-data="{ showAll: false }">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($features as $index => $feature)
                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group"
                            x-show="showAll || {{ $index }} < {{ $initialCount }}"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            style="{{ $index >= $initialCount ? 'display: none;' : '' }}">
                            <div
                                class="w-16 h-16 bg-brand-primary rounded-2xl flex items-center justify-center mb-8 group-hover:bg-brand-accent transition-colors duration-300">
                                @php
                                    // Icon mapping for common features
                                    $iconMap = [
                                        'reservations' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'staff' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                                        'analytics' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                                        'menu' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                                        'orders' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                                        'inventory' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                                        'default' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'
                                    ];
                                    $featureKey = strtolower($feature->feature_key ?? '');
                                    $iconPath = $iconMap[$featureKey] ?? $iconMap['default'];
                                @endphp
                                <svg class="w-8 h-8 text-white group-hover:text-brand-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-brand-primary mb-4">{{ $feature->name }}</h3>
                            <p class="text-slate-600 leading-relaxed mb-6">
                                {{ $feature->description ?? 'Powerful feature to enhance your restaurant operations.' }}
                            </p>
                        </div>
                    @endforeach
                </div>

                @if($features->count() > $initialCount)
                    <div class="text-center mt-12">
                        <button @click="showAll = !showAll"
                            class="inline-flex items-center text-brand-accent font-bold underline hover:text-brand-accent/80 transition-colors cursor-pointer group/btn"
                            type="button">
                            <span x-text="showAll ? 'Show Less' : 'View More Features'">View More Features</span>
                            <svg class="w-5 h-5 ml-2 transition-transform duration-300 group-hover/btn:translate-y-1"
                                :class="{ 'rotate-180 group-hover/btn:-translate-y-1': showAll }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection