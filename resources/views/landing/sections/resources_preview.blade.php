<section class="py-24 bg-brand-offwhite">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
            <div class="max-w-2xl">
                <h2 class="text-4xl font-extrabold text-brand-primary mb-4 tracking-tight">
                    {{ $section->title ?? 'Insights from our Experts' }}
                </h2>
                <p class="text-lg text-slate-600">
                    {{ $section->subtitle ?? 'Latest news, guides, and tips for the modern restaurant owner.' }}
                </p>
            </div>
            <a href="{{ route('resources') }}"
                class="inline-flex items-center px-6 py-3 bg-brand-primary text-white font-bold rounded-xl hover:bg-brand-secondary transition-all">
                View All Resources
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                    </path>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($section->items as $item)
                <div class="group cursor-pointer">
                    <div class="aspect-video bg-slate-200 rounded-[2rem] overflow-hidden mb-6 relative">
                        @php
                            $imageUrl = $item->getFirstMediaUrl('images');
                            if (!$imageUrl && filter_var($item->icon, FILTER_VALIDATE_URL)) {
                                $imageUrl = $item->icon;
                            }
                        @endphp

                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $item->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300"></div>
                        @endif
                        <div class="absolute top-4 left-4">
                            <span
                                class="bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-brand-primary">
                                {{ $item->subtitle ?? 'Article' }}
                            </span>
                        </div>
                    </div>
                    <h3
                        class="text-2xl font-bold text-brand-primary mb-3 group-hover:text-brand-accent transition-colors leading-snug">
                        {{ $item->title }}
                    </h3>
                    <p class="text-slate-600 line-clamp-2 mb-4 leading-relaxed">
                        {{ $item->description }}
                    </p>
                    <span
                        class="text-brand-primary font-bold inline-flex items-center group-hover:translate-x-1 transition-transform">
                        Read More &rarr;
                    </span>
                </div>
            @endforeach

            @if($section->items->isEmpty())
                <!-- Generic Placeholders -->
                @for($i = 1; $i <= 3; $i++)
                    <div class="opacity-40 select-none">
                        <div class="aspect-video bg-slate-200 rounded-[2rem] mb-6"></div>
                        <div class="h-8 bg-slate-200 rounded-lg w-3/4 mb-4"></div>
                        <div class="h-4 bg-slate-200 rounded-lg w-full mb-2"></div>
                        <div class="h-4 bg-slate-200 rounded-lg w-1/2"></div>
                    </div>
                @endfor
            @endif
        </div>
    </div>
</section>