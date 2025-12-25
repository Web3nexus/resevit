<section class="relative bg-brand-primary text-white overflow-hidden py-24 sm:py-32">
    <!-- Background Decor -->
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
                    {{ $section->subtitle ?? 'THE FUTURE OF DINING' }}
                </span>

                <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight leading-[1.1] mb-8">
                    {!! nl2br(e($section->title)) ?? 'Maximize Your Restaurant’s <span class="text-brand-accent">Potential</span>' !!}
                </h1>

                <p class="text-xl text-slate-300 mb-10 leading-relaxed max-w-xl">
                    {{ $section->content['description'] ?? 'Streamline reservations, optimize staff schedules, and delight customers with the world\'s most advanced restaurant management platform.' }}
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center px-8 py-4 bg-brand-accent text-brand-primary font-bold rounded-xl hover:scale-105 transition-transform shadow-xl shadow-brand-accent/20">
                        Get Started Free
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="#how-it-works"
                        class="inline-flex items-center justify-center px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition-all border border-white/10">
                        Watch Demo
                    </a>
                </div>

                <div class="mt-12 flex items-center space-x-6 grayscale opacity-60">
                    <span class="text-sm font-medium text-slate-500 uppercase tracking-widest">Trusted By</span>
                    <!-- Placeholder Logos -->
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
                    <!-- Dashboard Mockup/Image -->
                    @php
                        $firstItem = $section->items->first();
                        $imageUrl = null;
                        if ($firstItem) {
                            $imageUrl = $firstItem->getFirstMediaUrl('images');
                            if (!$imageUrl && filter_var($firstItem->icon, FILTER_VALIDATE_URL)) {
                                $imageUrl = $firstItem->icon;
                            }
                        }
                    @endphp

                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $firstItem->title ?? 'Dashboard Preview' }}"
                            class="rounded-2xl shadow-lg w-full h-auto object-cover">
                    @else
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
                    @endif

                    <!-- Floating Widget -->
                    <div
                        class="absolute bottom-10 -left-6 bg-brand-offwhite p-4 rounded-2xl shadow-2xl hidden md:block animate-bounce-slow">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-brand-accent flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-tighter">Reservations up
                                </p>
                                <p class="text-lg font-extrabold text-brand-primary">+24%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
    <style>
        @keyframes bounce-slow {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-bounce-slow {
            animation: bounce-slow 4s infinite ease-in-out;
        }
    </style>
@endpush