@extends('layouts.landing')

@section('content')
    <div class="bg-white min-h-screen py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Sidebar Navigation -->
                <aside class="lg:w-64 shrink-0">
                    <nav class="sticky top-24 space-y-8">
                        @foreach($allArticles as $category => $categoryArticles)
                            <div>
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">
                                    {{ str_replace('-', ' ', $category) }}</h3>
                                <ul class="space-y-3">
                                    @foreach($categoryArticles as $navArticle)
                                        <li>
                                            <a href="{{ route('docs.show', $navArticle->slug) }}"
                                                class="text-sm font-medium transition-colors {{ $article->id === $navArticle->id ? 'text-brand-accent' : 'text-slate-600 hover:text-brand-accent' }}">
                                                {{ $navArticle->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </nav>
                </aside>

                <!-- Article Content -->
                <article class="flex-1 max-w-3xl">
                    <nav class="flex mb-8 text-sm text-slate-500 font-medium" aria-label="Breadcrumb">
                        <ol class="flex items-center space-y-0 list-none p-0">
                            <li><a href="{{ route('docs.index') }}" class="hover:text-brand-accent">Docs</a></li>
                            <li class="mx-2 text-slate-300">/</li>
                            <li class="capitalize">{{ str_replace('-', ' ', $article->category) }}</li>
                        </ol>
                    </nav>

                    <header class="mb-12">
                        <h1 class="text-4xl font-extrabold text-slate-900 mb-4">{{ $article->title }}</h1>
                        @if($article->excerpt)
                            <p class="text-xl text-slate-500 leading-relaxed">{{ $article->excerpt }}</p>
                        @endif
                    </header>

                    <div class="prose prose-slate prose-lg max-w-none 
                        prose-headings:text-slate-900 prose-headings:font-bold
                        prose-a:text-brand-accent prose-a:no-underline hover:prose-a:underline
                        prose-strong:text-slate-900 prose-code:text-brand-accent prose-code:bg-slate-50 
                        prose-code:px-1 prose-code:rounded prose-pre:bg-slate-900 prose-pre:text-slate-100">
                        {!! $article->content !!}
                    </div>

                    <footer class="mt-16 pt-8 border-t border-slate-100">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-400">Last updated
                                {{ $article->updated_at->format('M d, Y') }}</span>
                            <a href="{{ route('docs.index') }}" class="text-brand-accent font-bold hover:underline">← Back
                                to index</a>
                        </div>
                    </footer>
                </article>
            </div>
        </div>
    </div>
@endsection