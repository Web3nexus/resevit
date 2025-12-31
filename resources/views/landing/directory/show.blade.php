@extends('layouts.landing')

@section('title', ($tenant->seo_title ?? $tenant->name . ' - Resevit Directory'))
@section('meta_description', $tenant->seo_description ?? $tenant->description)

@section('content')
    <!-- Business Banner -->
    <section class="relative h-[400px]">
        @if($tenant->cover_image)
            <img src="{{ \App\Helpers\StorageHelper::getUrl($tenant->cover_image) }}" alt="{{ $tenant->name }}"
                class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-slate-900 flex items-center justify-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-accent to-brand-primary"></div>
                </div>
                <span class="text-white text-9xl font-black relative z-10">{{ substr($tenant->name, 0, 1) }}</span>
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-full p-8 md:p-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <nav class="flex mb-4 text-slate-300 text-sm font-medium" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2">
                                <li><a href="{{ route('directory.index') }}"
                                        class="hover:text-white transition-colors">Directory</a></li>
                                <li><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg></li>
                                <li class="text-white">{{ $tenant->name }}</li>
                            </ol>
                        </nav>
                        <div class="flex items-center gap-4 mb-2">
                            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight">{{ $tenant->name }}
                            </h1>
                            @if($tenant->is_sponsored)
                                <span
                                    class="bg-brand-accent text-brand-primary text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded shadow-sm self-start mt-2 md:mt-4">Featured</span>
                            @endif
                        </div>
                        <div class="flex items-center space-x-4">
                            <span
                                class="bg-white/10 backdrop-blur-md text-white border border-white/20 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                                {{ $tenant->businessCategory?->name ?? 'Partner' }}
                            </span>
                            <div class="flex items-center text-slate-300 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $tenant->country ?? 'Global' }}
                            </div>
                            @if($tenant->reviews()->where('is_published', true)->count() > 0)
                                <div class="flex items-center space-x-2 pt-1">
                                    @include('components.star-rating', ['rating' => $tenant->averageRating(), 'class' => 'text-sm'])
                                    <span class="text-white text-xs font-bold">{{ number_format($tenant->averageRating(), 1) }}
                                        ({{ $tenant->reviews()->where('is_published', true)->count() }} reviews)</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="pb-2">
                        <a href="https://{{ $tenant->domain ?? $tenant->slug . '.' . parse_url(config('app.url'), PHP_URL_HOST) }}"
                            target="_blank"
                            class="inline-flex items-center px-8 py-4 bg-brand-accent text-brand-primary font-extrabold rounded-2xl hover:bg-white transition-all shadow-xl hover:scale-105">
                            Visit Website
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-20 bg-brand-offwhite">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Main Details -->
                <div class="lg:col-span-2 space-y-12">
                    <div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-slate-100">
                        <h2 class="text-2xl font-bold text-brand-primary mb-6">About the Business</h2>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-lg">
                            {!! nl2br(e($tenant->description ?? $tenant->name . ' is a premier partner in our directory, utilizing Resevit\'s advanced management platform to deliver exceptional experiences.')) !!}
                        </div>
                    </div>

                    <!-- Features Highlight (Dynamic could be added later) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-start space-x-4">
                            <div class="bg-brand-primary/5 p-3 rounded-xl text-brand-primary">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-brand-primary">Online Booking</h4>
                                <p class="text-xs text-slate-500 mt-1">Easily book your table or service online 24/7.</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-start space-x-4">
                            <div class="bg-brand-primary/5 p-3 rounded-xl text-brand-primary">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-brand-primary">Real-time Updates</h4>
                                <p class="text-xs text-slate-500 mt-1">Instant confirmation and status tracking.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="space-y-8">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-brand-primary">Customer Reviews</h2>
                            <div class="flex items-center space-x-2">
                                @include('components.star-rating', ['rating' => $tenant->averageRating()])
                                <span
                                    class="font-bold text-brand-primary">{{ number_format($tenant->averageRating(), 1) }}</span>
                            </div>
                        </div>

                        <div class="space-y-6">
                            @forelse($tenant->publishedReviews as $review)
                                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-brand-primary font-bold">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-brand-primary">{{ $review->user->name }}</h4>
                                                <p class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        @include('components.star-rating', ['rating' => $review->rating, 'class' => 'text-xs'])
                                    </div>
                                    @if($review->comment)
                                        <p class="text-slate-600 leading-relaxed">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @empty
                                <div class="bg-white rounded-3xl p-12 text-center border border-dashed border-slate-200">
                                    <p class="text-slate-400 italic">No reviews yet. Be the first to share your experience!</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Submission Form -->
                        @livewire('business-review-form', ['tenantId' => $tenant->id])
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="space-y-8">
                    <div class="bg-brand-primary text-white rounded-3xl p-8 shadow-xl">
                        <h3 class="text-xl font-bold mb-6">Partnership Notice</h3>
                        <p class="text-slate-400 text-sm mb-8 leading-relaxed">
                            This business is a verified partner on the Resevit platform. We ensure seamless integration and
                            top-tier management services for all our members.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-center text-sm font-medium">
                                <svg class="w-5 h-5 mr-3 text-brand-accent" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Verified Integrity
                            </div>
                            <div class="flex items-center text-sm font-medium">
                                <svg class="w-5 h-5 mr-3 text-brand-accent" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Official Partner
                            </div>
                        </div>
                        <hr class="my-8 border-white/10">
                        <a href="{{ route('register') }}"
                            class="block text-center text-xs font-black uppercase tracking-[0.2em] text-brand-accent hover:text-white transition-colors">Join
                            Resevit Today</a>
                    </div>

                    <!-- Contact/Share (Static placeholder) -->
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                        <h4 class="text-lg font-bold text-brand-primary mb-4 text-center">Share Profile</h4>
                        <div class="flex justify-center space-x-4">
                            <button
                                class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-brand-primary hover:text-white transition-all flex items-center justify-center">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button
                                class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-brand-primary hover:text-white transition-all flex items-center justify-center">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button
                                class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-brand-primary hover:text-white transition-all flex items-center justify-center">
                                <i class="fab fa-linkedin-in"></i>
                            </button>
                            <button
                                class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-brand-primary hover:text-white transition-all flex items-center justify-center">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection