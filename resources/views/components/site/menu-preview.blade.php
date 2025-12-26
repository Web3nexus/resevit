<section class="py-24" style="background-color: {{ $data['background_color'] ?? '#f8fafc' }};">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-extrabold text-brand-primary mb-4 tracking-tight">{{ $data['title'] }}</h2>
            <p class="text-slate-500 max-w-2xl mx-auto">Selected signature dishes crafted by our expert chefs.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($data['items'] ?? [] as $item)
                <div
                    class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <h4 class="text-xl font-bold text-brand-primary">{{ $item['name'] }}</h4>
                        <span class="text-brand-accent font-black">${{ number_format($item['price'], 2) }}</span>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        {{ $item['description'] ?? 'No description available.' }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="mt-16 text-center">
            <a href="{{ route('tenant.menu') }}"
                class="inline-flex items-center text-brand-primary font-bold hover:text-brand-accent transition-colors">
                View Full Digital Menu
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                    </path>
                </svg>
            </a>
        </div>
    </div>
</section>