<section class="py-20 bg-brand-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-12 text-center">
            @foreach($section->items as $item)
                <div>
                    <div class="text-4xl lg:text-5xl font-extrabold text-brand-accent mb-2">
                        {{ $item->title }}
                    </div>
                    <div class="text-slate-400 font-medium uppercase tracking-widest text-xs">
                        {{ $item->subtitle ?? $item->description }}
                    </div>
                </div>
            @endforeach

            @if($section->items->isEmpty())
                <!-- Default Stats if none in DB -->
                <div>
                    <div class="text-4xl lg:text-5xl font-extrabold text-brand-accent mb-2">500+</div>
                    <div class="text-slate-400 font-medium uppercase tracking-widest text-xs">Restaurants</div>
                </div>
                <div>
                    <div class="text-4xl lg:text-5xl font-extrabold text-brand-accent mb-2">2M+</div>
                    <div class="text-slate-400 font-medium uppercase tracking-widest text-xs">Reservations</div>
                </div>
                <div>
                    <div class="text-4xl lg:text-5xl font-extrabold text-brand-accent mb-2">99.9%</div>
                    <div class="text-slate-400 font-medium uppercase tracking-widest text-xs">Uptime</div>
                </div>
                <div>
                    <div class="text-4xl lg:text-5xl font-extrabold text-brand-accent mb-2">24/7</div>
                    <div class="text-slate-400 font-medium uppercase tracking-widest text-xs">Support</div>
                </div>
            @endif
        </div>
    </div>
</section>