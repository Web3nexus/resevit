<section class="py-24 bg-brand-offwhite overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-4xl font-extrabold text-brand-primary mb-6 tracking-tight">
                    {{ $section->title ?? 'Connect the Tools You Already Love' }}
                </h2>
                <p class="text-lg text-slate-600 mb-10 leading-relaxed">
                    {{ $section->subtitle ?? 'Resevit integrates seamlessly with your existing tech stack, from POS systems to social media platforms.' }}
                </p>

                <div class="grid grid-cols-2 gap-6">
                    @foreach($section->items as $item)
                        <div class="flex items-center space-x-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <div
                                class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center p-2 overflow-hidden">
                                @php
                                    $imageUrl = $item->getFirstMediaUrl('images');
                                    if (!$imageUrl && filter_var($item->icon, FILTER_VALIDATE_URL)) {
                                        $imageUrl = $item->icon;
                                    }
                                    $icon = $item->icon ?? 'heroicon-o-puzzle-piece';
                                @endphp

                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $item->title }}" class="w-full h-full object-contain">
                                @elseif(str_contains($icon, '<svg'))
                                    {!! $icon !!}
                                @elseif(str_starts_with($icon, 'heroicon-'))
                                    <x-filament::icon :icon="$icon" class="w-5 h-5 text-slate-400" />
                                @else
                                    <i class="{{ $icon }} text-slate-400"></i>
                                @endif
                            </div>
                            <span class="font-bold text-brand-primary">{{ $item->title }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="relative">
                <div class="absolute inset-0 bg-brand-accent/30 rounded-full blur-[100px] scale-75"></div>
                <div class="relative grid grid-cols-3 gap-4">
                    <!-- Visual representation of integration (abstract) -->
                    @for($i = 0; $i < 9; $i++)
                        <div
                            class="aspect-square bg-white rounded-3xl shadow-lg border border-slate-100 flex items-center justify-center p-6 @if($i % 2 == 0) animate-pulse @endif">
                            <div class="w-full h-full bg-slate-100 rounded-2xl"></div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</section>