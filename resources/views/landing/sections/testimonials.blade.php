<section class="py-24 bg-brand-offwhite overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <h2 class="text-4xl font-extrabold text-brand-primary mb-6 tracking-tight">Loved by the World's Best
                Restaurants</h2>
            <p class="text-lg text-slate-600">Don't just take our word for it. Here is what industry leaders say about
                Resevit.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($testimonials as $testimonial)
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col h-full">
                    <div class="flex items-center space-x-1 mb-6">
                        @for($i = 0; $i < ($testimonial->rating ?? 5); $i++)
                            <svg class="w-5 h-5 text-brand-accent" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        @endfor
                    </div>

                    <blockquote class="text-lg text-slate-700 leading-relaxed mb-8 flex-grow italic">
                        "{{ $testimonial->content }}"
                    </blockquote>

                    <div class="flex items-center space-x-4 mt-auto">
                        @if($testimonial->hasMedia('avatars'))
                            <img src="{{ $testimonial->getFirstMediaUrl('avatars') }}" alt="{{ $testimonial->name }}"
                                class="w-12 h-12 rounded-full object-cover ring-2 ring-brand-accent/20">
                        @else
                            <div
                                class="w-12 h-12 rounded-full bg-brand-primary flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($testimonial->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <div class="font-bold text-brand-primary">{{ $testimonial->name }}</div>
                            <div class="text-xs text-slate-500 font-medium uppercase tracking-wider">
                                {{ $testimonial->role }} @if($testimonial->company) at {{ $testimonial->company }} @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>