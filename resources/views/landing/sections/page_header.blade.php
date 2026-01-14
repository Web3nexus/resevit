<section class="relative bg-brand-primary text-white overflow-hidden py-20 sm:py-28">
    <!-- Background Decor -->
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-brand-accent rounded-full blur-[100px] -mr-48 -mt-48">
        </div>
        <div
            class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-brand-secondary rounded-full blur-[80px] -ml-32 -mb-32">
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="max-w-3xl mx-auto">
            @if($section->subtitle)
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-widest uppercase bg-brand-accent/20 text-brand-accent mb-6 border border-brand-accent/30">
                    {{ $section->subtitle }}
                </span>
            @endif

            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight leading-tight mb-8">
                {!! clean($section->title) !!}
            </h1>

            @if(isset($section->content['description']))
                <p class="text-xl text-slate-300 leading-relaxed max-w-2xl mx-auto">
                    {{ $section->content['description'] }}
                </p>
            @endif
        </div>
    </div>
</section>