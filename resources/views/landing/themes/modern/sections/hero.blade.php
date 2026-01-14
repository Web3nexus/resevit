<section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
    <!-- Atmospheric Background -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-full -z-10">
        <div
            class="absolute top-0 left-1/4 w-96 h-96 bg-brand-modern-accent/20 rounded-full blur-[120px] animate-pulse">
        </div>
        <div class="absolute top-1/2 right-1/4 w-96 h-96 bg-brand-modern-secondary/10 rounded-full blur-[100px] animate-pulse"
            style="animation-delay: 2s"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <!-- Badge -->
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-8 backdrop-blur-sm group hover:border-brand-modern-accent/50 transition-colors cursor-default">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-modern-accent opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-modern-accent"></span>
                </span>
                <span class="text-xs font-semibold tracking-wide text-brand-modern-text uppercase">
                    {{ $section->subtitle ?? 'The Future of Dining' }}
                </span>
                <i
                    class="fa-solid fa-chevron-right text-[10px] text-brand-modern-muted group-hover:translate-x-1 transition-transform"></i>
            </div>

            <!-- Title -->
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black tracking-tight leading-[1] mb-8 text-white">
                {!! clean($section->title ?? 'The OS for <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-modern-accent to-brand-modern-secondary">Elite Restaurants</span>') !!}
            </h1>

            <!-- Subtitle -->
            <p class="text-lg md:text-xl text-brand-modern-muted mb-12 leading-relaxed max-w-2xl mx-auto">
                {{ $section->content['description'] ?? 'Streamline reservations, optimize staff schedules, and delight customers with the world\'s most advanced restaurant management platform.' }}
            </p>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-20">
                @if(!empty($section->content['cta_text']))
                    <a href="{{ $section->content['cta_url'] ?? route('register') }}"
                        class="group relative inline-flex items-center justify-center px-10 py-4 font-bold text-white transition-all duration-200 bg-brand-modern-accent rounded-full hover:bg-opacity-80 shadow-[0_0_30px_rgba(125,64,255,0.4)]">
                        <span class="relative flex items-center gap-2">
                            {{ $section->content['cta_text'] }}
                            <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </span>
                    </a>
                @endif

                @if(!empty($section->content['secondary_cta_text']))
                    <a href="{{ $section->content['secondary_cta_url'] ?? '#how-it-works' }}"
                        class="inline-flex items-center justify-center px-10 py-4 font-bold text-white transition-all duration-200 bg-white/5 border border-white/10 rounded-full hover:bg-white/10 backdrop-blur-sm">
                        {{ $section->content['secondary_cta_text'] }}
                    </a>
                @endif
            </div>
        </div>

        <!-- Featured Image / Mockup -->
        <div class="mt-8 relative max-w-6xl mx-auto">
            <!-- Reflection Glow -->
            <div
                class="absolute -top-20 left-1/2 -translate-x-1/2 w-3/4 h-40 bg-brand-modern-accent/20 blur-[100px] -z-10 rounded-full">
            </div>

            <div class="relative rounded-2xl border border-white/10 bg-[#0d1117] p-2 shadow-2xl shadow-black/50">
                <div
                    class="absolute top-0 left-0 w-full h-full bg-gradient-to-t from-black/20 to-transparent pointer-events-none rounded-2xl">
                </div>

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

                <div class="overflow-hidden rounded-xl border border-white/5 bg-slate-900 aspect-[16/9] relative group">
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" alt="Resevit Dashboard"
                            class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-700">
                    @else
                        <!-- Placeholder GitHub style code mockup -->
                        <div
                            class="w-full h-full bg-[#0d1117] p-8 font-mono text-sm overflow-hidden select-none opacity-40">
                            <div class="flex gap-2 mb-4">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-500/50"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-500/50"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-green-500/50"></div>
                            </div>
                            <div class="space-y-3">
                                <div class="h-4 w-3/4 bg-white/5 rounded"></div>
                                <div class="h-4 w-1/2 bg-white/5 rounded"></div>
                                <div class="h-4 w-5/6 bg-white/5 rounded"></div>
                                <div class="pt-6">
                                    <div class="flex gap-4">
                                        <div
                                            class="h-12 w-12 rounded-lg bg-brand-modern-accent/20 border border-brand-modern-accent/30">
                                        </div>
                                        <div class="space-y-2 flex-grow">
                                            <div class="h-4 w-1/4 bg-white/10 rounded"></div>
                                            <div class="h-3 w-3/4 bg-white/5 rounded"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-6 space-y-3">
                                    <div class="h-4 w-full bg-white/5 rounded"></div>
                                    <div class="h-4 w-2/3 bg-white/5 rounded"></div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute inset-0 flex items-center justify-center">
                            <div
                                class="text-center p-8 bg-black/40 backdrop-blur-md rounded-2xl border border-white/10 max-w-md mx-auto transform translate-y-4 group-hover:translate-y-0 transition-transform">
                                <i class="fa-solid fa-microchip text-4xl text-brand-modern-accent mb-4 glow-text"></i>
                                <h3 class="text-xl font-bold text-white mb-2">Powered by AI</h3>
                                <p class="text-sm text-brand-modern-muted">Resevit automatically optimizes your seating and
                                    staffing based on real-time demand.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Floating Labels -->
                <div
                    class="absolute -bottom-6 -left-6 bg-brand-modern-card border border-brand-modern-border p-4 rounded-xl shadow-2xl hidden lg:block animate-float">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center text-green-500">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-brand-modern-muted font-bold uppercase tracking-tight">Revenue
                                Boost</p>
                            <p class="text-lg font-black text-white">+32.4%</p>
                        </div>
                    </div>
                </div>

                <div class="absolute -top-6 -right-6 bg-brand-modern-card border border-brand-modern-border p-4 rounded-xl shadow-2xl hidden lg:block animate-float"
                    style="animation-delay: -2s">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-brand-modern-accent/10 flex items-center justify-center text-brand-modern-accent">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-brand-modern-muted font-bold uppercase tracking-tight">Bookings
                                Optimized</p>
                            <p class="text-lg font-black text-white">4,821</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .animate-float {
            animation: float 5s infinite ease-in-out;
        }
    </style>
@endpush