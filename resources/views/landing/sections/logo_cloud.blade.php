<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-xs font-bold uppercase tracking-[0.3em] text-slate-400 mb-8">
            {{ $section->title ?? 'Trusted by industry leaders' }}
        </p>
        <div class="flex flex-wrap justify-center items-center gap-12 md:gap-20 opacity-40 grayscale">
            @foreach($section->items as $item)
                @php
                    $imageUrl = $item->getFirstMediaUrl('images');
                    if (!$imageUrl && filter_var($item->icon, FILTER_VALIDATE_URL)) {
                        $imageUrl = $item->icon;
                    }
                @endphp

                @if($imageUrl)
                    <img src="{{ $imageUrl }}" alt="{{ $item->title }}" class="h-8 w-auto">
                @else
                    <div class="text-2xl font-black text-slate-900">{{ $item->title ?? 'LOGO' }}</div>
                @endif
            @endforeach

            @if($section->items->isEmpty())
                <!-- Fallback Placeholders -->
                <div class="text-2xl font-black italic tracking-tighter">RESTO</div>
                <div class="text-2xl font-black italic tracking-tighter">DINER</div>
                <div class="text-2xl font-black italic tracking-tighter">BISTRO</div>
                <div class="text-2xl font-black italic tracking-tighter">GRUB</div>
            @endif
        </div>
    </div>
</div>