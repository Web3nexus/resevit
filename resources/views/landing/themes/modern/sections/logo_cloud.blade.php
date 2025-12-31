<div class="py-16 relative overflow-hidden group">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <p class="text-center text-[10px] font-black uppercase tracking-[0.4em] text-brand-modern-muted mb-12">
            {{ $section->title ?? 'Powering the world\'s most ambitious kitchens' }}
        </p>
        <div
            class="flex flex-wrap justify-center items-center gap-10 md:gap-24 opacity-30 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-700">
            @foreach($section->items as $item)
                @php
                    $imageUrl = $item->getFirstMediaUrl('images');
                    if (!$imageUrl && filter_var($item->icon, FILTER_VALIDATE_URL)) {
                        $imageUrl = $item->icon;
                    }
                @endphp

                @if($imageUrl)
                    <img src="{{ $imageUrl }}" alt="{{ $item->title }}"
                        class="h-6 md:h-8 w-auto transition-transform hover:scale-110">
                @else
                    <div
                        class="text-xl md:text-2xl font-black text-white tracking-tighter transition-all hover:text-brand-modern-accent">
                        {{ $item->title ?? 'LOGO' }}</div>
                @endif
            @endforeach

            @if($section->items->isEmpty())
                <!-- Fallback Placeholders -->
                <div
                    class="text-xl md:text-2xl font-black text-white tracking-tighter hover:text-brand-modern-accent transition-colors">
                    THE GRILL</div>
                <div
                    class="text-xl md:text-2xl font-black text-white tracking-tighter hover:text-brand-modern-secondary transition-colors">
                    NOVA</div>
                <div
                    class="text-xl md:text-2xl font-black text-white tracking-tighter hover:text-brand-modern-accent transition-colors">
                    SAVOR</div>
                <div
                    class="text-xl md:text-2xl font-black text-white tracking-tighter hover:text-brand-modern-secondary transition-colors">
                    ALTOS</div>
            @endif
        </div>
    </div>
</div>