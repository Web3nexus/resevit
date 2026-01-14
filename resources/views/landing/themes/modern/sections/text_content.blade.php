<section class="py-20 lg:py-32 relative overflow-hidden">
    <!-- Atmospheric Background -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-full -z-10 pointer-events-none">
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand-modern-accent/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-black text-white mb-6 tracking-tight">{{ $section->title }}</h2>
            @if($section->subtitle)
                <p class="text-lg text-brand-modern-muted max-w-2xl mx-auto">{{ $section->subtitle }}</p>
            @endif
        </div>

        <div
            class="prose prose-lg prose-invert mx-auto text-brand-modern-text/90 
            prose-headings:text-white prose-headings:font-bold 
            prose-a:text-brand-modern-accent prose-a:no-underline hover:prose-a:underline
            prose-strong:text-white prose-code:text-brand-modern-accent prose-code:bg-white/5 prose-code:px-1 prose-code:rounded
            prose-blockquote:border-l-brand-modern-accent prose-blockquote:bg-white/5 prose-blockquote:py-2 prose-blockquote:pr-4">
            {!! clean($section->content['body'] ?? '') !!}
        </div>
    </div>
</section>