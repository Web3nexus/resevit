@extends('layouts.landing')

@section('content')
    <div class="bg-slate-50 min-h-screen py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-extrabold text-slate-900 sm:text-5xl">Documentation</h1>
                <p class="mt-4 text-xl text-slate-600">Everything you need to know about Resevit.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($articles as $category => $categoryArticles)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-brand-accent/10 rounded-lg">
                                <svg class="w-6 h-6 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-slate-900 capitalize">{{ str_replace('-', ' ', $category) }}</h2>
                        </div>
                        <ul class="space-y-4">
                            @foreach($categoryArticles as $article)
                                <li>
                                    <a href="{{ route('docs.show', $article->slug) }}" class="group flex items-start gap-2">
                                        <svg class="w-5 h-5 text-slate-300 group-hover:text-brand-accent mt-0.5 shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                            </path>
                                        </svg>
                                        <div>
                                            <h3 class="text-slate-700 group-hover:text-brand-accent font-medium transition-colors">
                                                {{ $article->title }}</h3>
                                            @if($article->excerpt)
                                                <p class="text-sm text-slate-500 line-clamp-1 mt-0.5">{{ $article->excerpt }}</p>
                                            @endif
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            @if($articles->isEmpty())
                <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-slate-900">No documentation yet</h3>
                    <p class="mt-1 text-sm text-slate-500">Check back later for help articles.</p>
                </div>
            @endif
        </div>
    </div>
@endsection