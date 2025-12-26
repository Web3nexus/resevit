@extends('layouts.landing')

@section('title', $title . ' - ' . config('app.name'))

@section('content')
    <section class="py-24 bg-brand-offwhite min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 md:p-16 rounded-3xl shadow-sm border border-slate-200">
                <nav class="mb-8">
                    <a href="{{ route('home') }}"
                        class="text-brand-accent font-semibold flex items-center hover:translate-x-1 transition-transform">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Home
                    </a>
                </nav>

                <h1 class="text-4xl font-extrabold text-brand-primary mb-12 tracking-tight">{{ $title }}</h1>

                <div
                    class="prose prose-lg prose-slate max-w-none prose-headings:text-brand-primary prose-a:text-brand-accent">
                    {!! $content !!}
                </div>

                <div class="mt-16 pt-8 border-t border-slate-100 text-slate-500 text-sm">
                    Last updated: {{ date('F d, Y') }}
                </div>
            </div>
        </div>
    </section>
@endsection