@extends('layouts.landing')

@section('content')
    <div class="bg-white min-h-screen py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="prose prose-slate prose-lg max-w-none 
                prose-headings:text-slate-900 prose-headings:font-bold
                prose-a:text-brand-accent prose-a:no-underline hover:prose-a:underline
                prose-strong:text-slate-900 prose-code:text-brand-accent prose-code:bg-slate-50 
                prose-code:px-1 prose-code:rounded">

                <header class="mb-12 not-prose">
                    <nav class="flex mb-4 text-sm text-slate-500 font-medium" aria-label="Breadcrumb">
                        <ol class="flex items-center space-y-0 list-none p-0">
                            <li><a href="/" class="hover:text-brand-accent">Home</a></li>
                            <li class="mx-2 text-slate-300">/</li>
                            <li class="text-slate-900">Legal</li>
                        </ol>
                    </nav>
                    <h1 class="text-4xl font-extrabold text-slate-900">{{ $document->title }}</h1>
                    <p class="mt-4 text-sm text-slate-400">Last updated {{ $document->updated_at->format('F d, Y') }}</p>
                </header>

                {!! $document->content !!}
            </article>

            <footer class="mt-16 pt-8 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-500">&copy; {{ date('Y') }} Resevit. All rights reserved.</p>
                    <a href="/" class="text-brand-accent font-bold hover:underline">Return to Home</a>
                </div>
            </footer>
        </div>
    </div>
@endsection