<div class="bg-brand-accent py-2 text-center text-brand-primary text-sm font-bold tracking-wide">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-center space-x-3">
        <span>{{ $section->title ?? 'New Feature: AI-Powered Table Management' }}</span>
        <a href="{{ $section->content['link_url'] ?? '#' }}"
            class="underline decoration-2 underline-offset-2 hover:opacity-80 transition-opacity">
            {{ $section->content['link_text'] ?? 'Learn more' }} &rarr;
        </a>
    </div>
</div>