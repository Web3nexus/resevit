<section class="py-24 bg-brand-offwhite">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-20">
            @if($section->subtitle)
                <span class="text-brand-accent font-bold tracking-widest uppercase text-sm mb-4 block">
                    {{ $section->subtitle }}
                </span>
            @endif
            <h2 class="text-4xl font-extrabold text-brand-primary mb-6 tracking-tight">
                {{ $section->title ?? 'Powerful Features to Control Your Success' }}
            </h2>
            <p class="text-lg text-slate-600 leading-relaxed">
                {{ $section->content['description'] ?? 'Everything you need to run your restaurant, all in one intuitive platform.' }}
            </p>
        </div>

        @php
            $displayItems = $section->items;
            if (($section->subtitle === 'CORE CAPABILITIES' || $section->items->isEmpty()) && isset($pricing_features)) {
                $displayItems = $pricing_features->map(function ($f) {
                    return (object) [
                        'title' => $f->name,
                        'description' => $f->description,
                        'icon' => 'heroicon-o-check-circle',
                        'link_url' => null
                    ];
                });
            }
            $initialDisplayCount = 6; // Show 6 items initially
        @endphp

        <div x-data="{ showAllFeatures: false }">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($displayItems as $index => $item)
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-slate-200/50 hover:-translate-y-2 transition-all duration-300 group"
                        x-show="showAllFeatures || {{ $index }} < {{ $initialDisplayCount }}"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        style="{{ $index >= $initialDisplayCount ? 'display: none;' : '' }}">
                        <div
                            class="w-16 h-16 bg-brand-primary rounded-2xl flex items-center justify-center mb-8 group-hover:bg-brand-accent transition-colors duration-300 shadow-lg shadow-brand-primary/10 overflow-hidden">
                            @php
                                $imageUrl = null;
                                if (method_exists($item, 'getFirstMediaUrl')) {
                                    $imageUrl = $item->getFirstMediaUrl('images');
                                }
                                if (!$imageUrl && isset($item->icon) && filter_var($item->icon, FILTER_VALIDATE_URL)) {
                                    $imageUrl = $item->icon;
                                }

                                $icon = $item->icon ?? 'heroicon-o-sparkles';
                            @endphp

                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                            @elseif(str_contains($icon, '<svg'))
                                <div class="w-8 h-8 text-white group-hover:text-brand-primary transition-colors duration-300">
                                    {!! $icon !!}
                                </div>
                            @elseif(str_starts_with($icon, 'heroicon-'))
                                <div class="w-8 h-8 text-white group-hover:text-brand-primary transition-colors duration-300">
                                    <x-filament::icon :icon="$icon" class="w-full h-full" />
                                </div>
                            @else
                                <i class="{{ $icon }} text-2xl text-white group-hover:text-brand-primary"></i>
                            @endif
                        </div>

                        <h3 class="text-2xl font-bold text-brand-primary mb-4">{{ $item->title }}</h3>
                        <p class="text-slate-600 leading-relaxed mb-6">
                            {{ $item->description }}
                        </p>

                        @if(isset($item->link_url) && $item->link_url)
                            <a href="{{ $item->link_url }}"
                                class="inline-flex items-center text-brand-primary font-bold group-hover:text-brand-accent transition-colors">
                                {{ $item->link_text ?? 'Learn More' }}
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($displayItems->count() > $initialDisplayCount)
                <div class="text-center mt-12">
                    <button @click="showAllFeatures = !showAllFeatures"
                        class="inline-flex items-center text-brand-accent font-bold underline hover:text-brand-accent/80 transition-colors cursor-pointer group/btn"
                        type="button">
                        <span x-text="showAllFeatures ? 'Show Less' : 'View More'">View More</span>
                        <svg class="w-5 h-5 ml-2 transition-transform duration-300 group-hover/btn:translate-y-1"
                            :class="{ 'rotate-180 group-hover/btn:-translate-y-1': showAllFeatures }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
</section>