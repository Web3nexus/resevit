@extends('layouts.landing')

@section('title', 'Business Directory - Resevit')

@section('content')
    <!-- Hero Section -->
    <section class="py-20 bg-brand-primary text-white text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-6 tracking-tight">Explore Our Partners</h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Discover thousands of world-class restaurants and businesses powered by Resevit.
            </p>
        </div>
    </section>

    <!-- Filter & Search -->
    <section class="py-8 bg-white border-b border-slate-100 sticky top-20 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('directory.index') }}"
                    class="px-6 py-2 rounded-full text-sm font-semibold transition-all {{ !request('category') ? 'bg-brand-primary text-white shadow-md' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    All Businesses
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('directory.index', ['category' => $category->slug]) }}"
                        class="px-6 py-2 rounded-full text-sm font-semibold transition-all {{ request('category') == $category->slug ? 'bg-brand-primary text-white shadow-md' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Directory Grid -->
    <section class="py-16 bg-brand-offwhite min-h-[60vh]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($businesses->isEmpty())
                <div class="text-center py-20">
                    <div class="bg-white rounded-3xl p-12 shadow-sm inline-block max-w-lg">
                        <svg class="w-20 h-20 text-slate-200 mx-auto mb-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <h2 class="text-2xl font-bold text-brand-primary mb-2">No businesses found</h2>
                        <p class="text-slate-500 mb-8">Try adjusting your filters or check back later as new partners join our
                            platform.</p>
                        <a href="{{ route('directory.index') }}" class="text-brand-primary font-bold hover:underline">Clear all
                            filters</a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($businesses as $business)
                        <div
                            class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col {{ $business->is_sponsored ? 'ring-2 ring-brand-accent/50' : 'border border-slate-100' }}">
                            <!-- Cover Image -->
                            <div class="relative h-56 overflow-hidden">
                                @if($business->cover_image)
                                    <img src="{{ \App\Helpers\StorageHelper::getUrl($business->cover_image) }}"
                                        alt="{{ $business->name }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                                        <span class="text-slate-300 text-5xl font-black">{{ substr($business->name, 0, 1) }}</span>
                                    </div>
                                @endif

                                @if($business->is_sponsored)
                                    <div class="absolute top-4 left-4">
                                        <span
                                            class="bg-brand-accent text-brand-primary text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded shadow-sm">Featured</span>
                                    </div>
                                @endif

                                <div class="absolute top-4 right-4">
                                    <span
                                        class="bg-white/90 backdrop-blur-sm text-brand-primary text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                                        {{ $business->businessCategory?->name ?? 'General' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-8 flex flex-col grow">
                                <h3
                                    class="text-xl font-bold text-brand-primary mb-2 group-hover:text-brand-accent transition-colors">
                                    {{ $business->name }}
                                </h3>
                                <p class="text-slate-500 text-sm line-clamp-2 mb-6 grow">
                                    {{ $business->description ?? 'Proudly using Resevit to provide exceptional service and seamless management.' }}
                                </p>

                                <div class="flex items-center justify-between mt-auto pt-6 border-t border-slate-50">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active</span>
                                    </div>
                                    <a href="{{ route('directory.show', $business->slug) }}"
                                        class="text-sm font-bold text-brand-primary hover:text-brand-accent transition-colors flex items-center">
                                        View Profile
                                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-16">
                    {{ $businesses->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection