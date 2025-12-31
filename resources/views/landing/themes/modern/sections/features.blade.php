<section class="py-24 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-20">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
                <div class="max-w-2xl">
                    @if($section->subtitle)
                        <span
                            class="text-brand-modern-accent font-bold tracking-widest uppercase text-xs mb-4 block glow-text">
                            {{ $section->subtitle }}
                        </span>
                    @endif
                    <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight">
                        {{ $section->title ?? 'Platform Capabilities' }}
                    </h2>
                    <p class="text-lg text-brand-modern-muted leading-relaxed">
                        {{ $section->content['description'] ?? 'Everything you need to run your restaurant, all in one intuitive platform.' }}
                    </p>
                </div>
                <div class="hidden md:block">
                    <a href="{{ route('features') }}"
                        class="inline-flex items-center gap-2 text-sm font-bold text-white hover:text-brand-modern-accent transition-colors group">
                        Explore Full Documentation
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>

        @php
            $displayItems = $section->items;
            if (($section->subtitle === 'CORE CAPABILITIES' || $section->items->isEmpty()) && isset($pricing_features)) {
                $displayItems = $pricing_features->map(function ($f) {
                    return (object) [
                        'title' => $f->name,
                        'description' => $f->description,
                        'icon' => 'fa-solid fa-circle-check',
                        'link_url' => null
                    ];
                });
            }
            $initialDisplayCount = 6;
        @endphp

        <div x-data="{ showAllFeatures: false }">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($displayItems as $index => $item)
                    <div class="gradient-border p-8 group transition-all duration-500 hover:-translate-y-1"
                        x-show="showAllFeatures || {{ $index }} < {{ $initialDisplayCount }}"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        style="{{ $index >= $initialDisplayCount ? 'display: none;' : '' }}">

                        <div class="flex flex-col h-full">
                            <div class="mb-6 relative">
                                <div
                                    class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-brand-modern-accent group-hover:scale-110 group-hover:bg-brand-modern-accent group-hover:text-white transition-all duration-300">
                                    @php
                                        $icon = $item->icon ?? 'fa-solid fa-sparkles';
                                        $isHeron = str_starts_with($icon, 'heroicon-');
                                    @endphp

                                    @if($isHeron)
                                        <x-filament::icon :icon="$icon" class="w-6 h-6" />
                                    @elseif(str_contains($icon, '<svg'))
                                        <div class="w-6 h-6">{!! $icon !!}</div>
                                    @else
                                        <i class="{{ $icon }} text-lg"></i>
                                    @endif
                                </div>
                            </div>

                            <h3
                                class="text-xl font-bold text-white mb-4 group-hover:text-brand-modern-accent transition-colors">
                                {{ $item->title }}</h3>
                            <p class="text-brand-modern-muted text-sm leading-relaxed flex-grow">
                                {{ $item->description }}
                            </p>

                            @if(isset($item->link_url) && $item->link_url)
                                <div class="mt-8">
                                    <a href="{{ $item->link_url }}"
                                        class="inline-flex items-center gap-2 text-xs font-bold text-white uppercase tracking-widest group-hover:text-brand-modern-accent transition-colors">
                                        {{ $item->link_text ?? 'Learn More' }}
                                        <i
                                            class="fa-solid fa-chevron-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($displayItems->count() > $initialDisplayCount)
                <div class="text-center mt-12">
                    <button @click="showAllFeatures = !showAllFeatures"
                        class="px-8 py-3 rounded-full bg-white/5 border border-white/10 text-white font-bold hover:bg-white/10 transition-all flex items-center gap-3 mx-auto"
                        type="button">
                        <span x-text="showAllFeatures ? 'Show Less' : 'View All Capabilities'">View More</span>
                        <i class="fa-solid fa-chevron-down transition-transform duration-300"
                            :class="{ 'rotate-180': showAllFeatures }"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>
</section>