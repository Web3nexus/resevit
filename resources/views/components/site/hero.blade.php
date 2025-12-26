<section class="relative h-[80vh] flex items-center justify-center overflow-hidden bg-slate-900 text-white">
    <div class="absolute inset-0 z-0">
        @if(!empty($data['overlay_color']))
            <div class="absolute inset-0 z-10" style="background-color: {{ $data['overlay_color'] }}"></div>
        @else
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-transparent z-10"></div>
        @endif

        @if(!empty($data['background_image']))
            <img src="{{ Storage::url($data['background_image']) }}" alt="{{ $data['headline'] }}"
                class="w-full h-full object-cover">
        @else
            <img src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?auto=format&fit=crop&w=1920&q=80"
                alt="{{ $data['headline'] }}" class="w-full h-full object-cover opacity-60">
        @endif
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-left w-full">
        <div class="max-w-2xl">
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 animate-fade-in-up"
                style="{{ !empty($data['text_color']) ? 'color: ' . $data['text_color'] : '' }}">
                {{ $data['headline'] }}
            </h1>
            <p class="text-xl md:text-2xl text-slate-300 mb-8 leading-relaxed animate-fade-in-up delay-100"
                style="{{ !empty($data['text_color']) ? 'color: ' . $data['text_color'] . '; opacity: 0.9;' : '' }}">
                {{ $data['subheadline'] }}
            </p>
            <div class="animate-fade-in-up delay-200">
                <a href="{{ $data['cta_url'] ?? '#reserve' }}"
                    class="inline-flex items-center px-8 py-4 bg-white text-brand-primary font-black rounded-2xl hover:bg-brand-accent hover:text-brand-primary transition-all shadow-2xl hover:scale-105">
                    {{ $data['cta_text'] }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>