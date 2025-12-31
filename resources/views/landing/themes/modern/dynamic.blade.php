@extends($layout ?? 'layouts.landing')

@php
    $metaTitle = $page->meta_title ?? $page->title;
    $metaDescription = $page->meta_description;
    $theme = $theme ?? 'default';
@endphp

@section('title', $metaTitle)
@section('meta_description', $metaDescription)

@section('content')
    <div class="space-y-0">
        @foreach($sections as $section)
            {{-- Skip hero sections on non-home pages to avoid duplicates --}}
            @if($section->type === 'hero' && $page->slug !== 'home')
                @continue
            @endif

            @php
                $themePath = "landing.themes.{$theme}.sections.{$section->type}";
                $defaultPath = "landing.sections.{$section->type}";
                $partialPath = view()->exists($themePath) ? $themePath : $defaultPath;
            @endphp

            @if(view()->exists($partialPath))
                @include($partialPath, ['section' => $section])
            @else
                <div class="py-20 text-center text-brand-modern-muted border-y border-brand-modern-border bg-brand-modern-bg">
                    <p class="font-mono text-xs uppercase tracking-widest opacity-50">Debug: Section type [{{ $section->type }}]
                        missing view partial</p>
                </div>
            @endif
        @endforeach
    </div>
@endsection