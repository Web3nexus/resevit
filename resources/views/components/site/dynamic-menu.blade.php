@php
    $categories = \App\Models\Category::with([
        'menuItems' => function ($query) {
            $query->where('is_active', true)->where('is_available', true)->orderBy('sort_order');
        }
    ])
        ->whereIn('id', $data['category_ids'] ?? [])
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();
@endphp

<section class="py-24" style="background-color: {{ $data['background_color'] ?? '#f8fafc' }};">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-extrabold text-brand-primary mb-4 tracking-tight uppercase">{{ $data['title'] }}
            </h2>
            @if(!empty($data['subtitle']))
                <p class="text-slate-500 max-w-2xl mx-auto">{{ $data['subtitle'] }}</p>
            @endif
        </div>

        @foreach($categories as $category)
            @if($category->menuItems->count() > 0)
                <div class="mb-20 last:mb-0">
                    <div class="flex items-center space-x-4 mb-10">
                        <h3 class="text-2xl font-black text-brand-primary uppercase tracking-wider">{{ $category->name }}</h3>
                        <div class="h-px bg-slate-200 flex-grow"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                        @foreach($category->menuItems as $item)
                            <div class="flex space-x-6 group">
                                @if($item->image_path)
                                    <div class="flex-shrink-0 w-24 h-24 rounded-2xl overflow-hidden shadow-md">
                                        <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                @endif
                                <div class="flex-grow">
                                    <div class="flex justify-between items-baseline mb-2">
                                        <h4 class="text-lg font-bold text-brand-primary uppercase">{{ $item->name }}</h4>
                                        <div class="flex-grow mx-4 border-b border-dotted border-slate-300"></div>
                                        <span
                                            class="text-lg font-black text-brand-accent">${{ number_format($item->base_price, 2) }}</span>
                                    </div>
                                    @if($item->description)
                                        <p class="text-slate-500 text-sm leading-relaxed line-clamp-2">
                                            {{ $item->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        <div class="mt-20 text-center">
            <a href="{{ route('tenant.menu') }}"
                class="inline-flex items-center px-8 py-3 bg-brand-primary text-white font-bold rounded-xl hover:bg-brand-accent hover:text-brand-primary transition-all shadow-lg hover:scale-105">
                View Full Digital Menu
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                    </path>
                </svg>
            </a>
        </div>
    </div>
</section>