<section class="py-20 bg-brand-primary text-white relative overflow-hidden" @if(!empty($data['background_image']))
    style="background-image: url('{{ Storage::url($data['background_image']) }}'); background-size: cover; background-position: center;"
@endif>
    @if(!empty($data['background_image']))
        <div class="absolute inset-0 bg-black/60 z-0"></div>
    @endif
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-4xl font-extrabold mb-8 {{ empty($data['text_color']) ? 'bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent' : '' }}"
            style="{{ !empty($data['text_color']) ? 'color: ' . $data['text_color'] : '' }}">
            {{ $data['headline'] }}
        </h2>
        <a href="#reserve"
            class="inline-flex items-center px-10 py-5 bg-brand-accent text-brand-primary font-black rounded-full hover:scale-105 transition-all shadow-xl"
            style="{{ !empty($data['button_color']) ? 'background-color: ' . $data['button_color'] . '; border-color: ' . $data['button_color'] . ';' : '' }}">
            {{ $data['button_text'] }}
        </a>
    </div>
</section>