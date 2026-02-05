@extends('layouts.landing')

@section('title', 'Business Not Found')

@section('content')
    <div class="min-h-[75vh] flex flex-col items-center justify-center px-4 py-24 text-center bg-brand-offwhite">
        <div class="max-w-4xl w-full space-y-12">
            <!-- Illustration -->
            <div class="relative flex justify-center">
                <div class="relative flex items-center justify-center">
                    <span
                        class="text-[180px] md:text-[240px] font-black text-slate-100/60 dark:text-gray-800/20 select-none tracking-tighter leading-none">OOPS</span>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="relative">
                            <i
                                class="fa-solid fa-store-slash text-brand-primary text-[100px] md:text-[140px] animate-pulse opacity-80"></i>
                            <div
                                class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-24 h-4 bg-slate-900/10 rounded-[100%] blur-lg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Text Content -->
            <div class="space-y-6 max-w-3xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight leading-tight">
                    Business Not Found
                </h1>
                <p class="text-xl text-slate-600 leading-relaxed font-medium">
                    The business you are looking for does not exist or the link might be incorrect. Please check the URL and
                    try again.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap justify-center gap-5 pt-4">
                <a href="{{ config('app.url') }}"
                    class="inline-flex items-center px-8 py-4 border border-transparent text-lg font-bold rounded-2xl text-white bg-brand-primary hover:bg-brand-secondary transition-all shadow-xl hover:shadow-brand-primary/20 hover:-translate-y-1">
                    <i class="fa-solid fa-house mr-2"></i> Go to resovit.com
                </a>
                <button onclick="window.history.back()"
                    class="inline-flex items-center px-8 py-4 border-2 border-slate-200 text-lg font-bold rounded-2xl text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 transition-all shadow-md hover:-translate-y-1">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Go Back
                </button>
            </div>
        </div>
    </div>
@endsection