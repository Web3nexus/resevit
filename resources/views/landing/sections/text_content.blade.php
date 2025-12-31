<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl mb-4">{{ $section->title }}</h2>
            @if($section->subtitle)
                <p class="text-lg text-gray-600">{{ $section->subtitle }}</p>
            @endif
        </div>

        <div class="prose prose-lg prose-indigo mx-auto text-gray-600">
            {!! $section->content['body'] ?? '' !!}
        </div>
    </div>
</section>