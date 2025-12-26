<section id="reserve" class="py-24 relative overflow-hidden"
    style="background-color: {{ $data['background_color'] ?? '#f8fafc' }};">
    <div
        class="absolute top-0 right-0 w-64 h-64 bg-brand-primary opacity-5 rounded-full -translate-y-1/2 translate-x-1/2">
    </div>
    <div
        class="absolute bottom-0 left-0 w-96 h-96 bg-brand-accent opacity-5 rounded-full translate-y-1/2 -translate-x-1/2">
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-extrabold text-brand-primary mb-4 tracking-tight uppercase">{{ $data['title'] }}
            </h2>
            <p class="text-slate-500 max-w-lg mx-auto">{{ $data['description'] }}</p>
        </div>

        <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-2xl border border-slate-100">
            @livewire('public-reservation-form')
        </div>
    </div>
</section>