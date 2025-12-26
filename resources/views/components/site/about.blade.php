<section class="py-24 overflow-hidden" style="background-color: {{ $data['background_color'] ?? '#ffffff' }};">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="relative">
                <div class="absolute -top-4 -left-4 w-24 h-24 bg-brand-accent rounded-3xl -z-10 opacity-20"></div>
                @if(!empty($data['image']))
                    <img src="{{ Storage::url($data['image']) }}" alt="Our Story" class="rounded-3xl shadow-2xl w-full">
                @else
                    <img src="https://images.unsplash.com/photo-1550966841-3ee32c943183?auto=format&fit=crop&w=800&q=80"
                        alt="Our Story" class="rounded-3xl shadow-2xl w-full">
                @endif
            </div>
            <div>
                <span class="text-brand-accent font-black uppercase tracking-[0.3em] text-xs mb-4 block">Legacy &
                    Passion</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-brand-primary mb-8 tracking-tight">
                    {{ $data['title'] }}
                </h2>
                <div class="prose prose-slate prose-lg text-slate-600">
                    {!! nl2br(e($data['content'])) !!}
                </div>
            </div>
        </div>
    </div>
</section>