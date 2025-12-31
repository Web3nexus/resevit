@extends('layouts.landing')

@php
    $settings = \App\Models\PlatformSetting::current();
    $config = $settings->error_pages['419'] ?? [];
    $title = $config['title'] ?? 'Session Expired';
    $description = $config['description'] ?? 'Your session has timed out due to inactivity. For your security, please refresh the page and try again.';
    $image = isset($config['image']) && $config['image'] ? \Illuminate\Support\Facades\Storage::url($config['image']) : null;
@endphp

@section('title', $title)

@section('content')
    <div class="min-h-[75vh] flex flex-col items-center justify-center px-4 py-24 text-center bg-brand-offwhite">
        <div class="max-w-4xl w-full space-y-12">
            <!-- Illustration -->
            <div class="relative flex justify-center">
                @if($image)
                    <div class="relative group">
                        <img src="{{ $image }}" alt="Error 419" class="max-h-[450px] w-auto animate-float drop-shadow-2xl">
                        <div
                            class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-48 h-6 bg-slate-900/10 rounded-[100%] blur-xl opacity-50">
                        </div>
                    </div>
                @else
                    <!-- High-quality default fallback -->
                    <div class="relative flex items-center justify-center">
                        <span
                            class="text-[200px] md:text-[280px] font-black text-slate-100/50 dark:text-gray-800/20 select-none tracking-tighter leading-none">419</span>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="relative">
                                <i
                                    class="fa-solid fa-clock-rotate-left text-brand-primary text-[100px] md:text-[140px] animate-float"></i>
                                <div
                                    class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-24 h-4 bg-slate-900/10 rounded-[100%] blur-lg">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Text Content -->
            <div class="space-y-6 max-w-3xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight leading-tight">
                    {{ $title }}
                </h1>
                <p class="text-xl text-slate-600 leading-relaxed font-medium">
                    {{ $description }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap justify-center gap-5 pt-4">
                <button onclick="window.location.reload()"
                    class="inline-flex items-center px-8 py-4 border border-transparent text-lg font-bold rounded-2xl text-white bg-brand-primary hover:bg-brand-secondary transition-all shadow-xl hover:shadow-brand-primary/20 hover:-translate-y-1">
                    <i class="fa-solid fa-arrows-rotate mr-2"></i> Refresh Page
                </button>
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center px-8 py-4 border-2 border-slate-200 text-lg font-bold rounded-2xl text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 transition-all shadow-md hover:-translate-y-1">
                        <i class="fa-solid fa-house mr-2"></i> Go Home
                    </a>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {
            0% {
                transform: translateY(0px) rotate(0deg);
            }

            33% {
                transform: translateY(-30px) rotate(2deg);
            }

            66% {
                transform: translateY(-10px) rotate(-1deg);
            }

            100% {
                transform: translateY(0px) rotate(0deg);
            }
        }

        .animate-float {
            animation: float 8s ease-in-out infinite;
        }
    </style>
@endsection