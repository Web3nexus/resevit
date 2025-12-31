@extends('layouts.landing')

@section('title', 'Food Ordering - Resevit')

@section('content')
    <!-- Hero Section -->
    <section class="py-20 bg-[#FF4F18] text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="currentColor"></path>
            </svg>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <h1 class="text-4xl md:text-6xl font-black mb-6 tracking-tight">Crave it? <span class="text-black">Order it.</span></h1>
            <p class="text-xl text-white/90 max-w-2xl mx-auto leading-relaxed font-medium">
                Delicious food from your favorite local restaurants, delivered to your doorstep or ready for pickup.
            </p>
        </div>
    </section>

    <!-- Filter & search (Simplified for food) -->
    <section class="py-12 bg-white border-b border-slate-100 sticky top-20 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-center gap-6">
                <a href="{{ route('food.index') }}"
                    class="flex flex-col items-center group transition-all">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center transition-all {{ !request('category') ? 'bg-[#FF4F18] text-white shadow-lg' : 'bg-slate-50 text-slate-400 group-hover:bg-slate-100' }}">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </div>
                    <span class="mt-2 text-xs font-bold uppercase tracking-wider {{ !request('category') ? 'text-black' : 'text-slate-400' }}">All</span>
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('food.index', ['category' => $category->slug]) }}"
                        class="flex flex-col items-center group transition-all">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center transition-all {{ request('category') == $category->slug ? 'bg-[#FF4F18] text-white shadow-lg' : 'bg-slate-50 text-slate-400 group-hover:bg-slate-100' }}">
                            @php
                                $icon = match($category->slug) {
                                    'restaurant' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m10 0a2 2 0 100-4m0 4a2 2 0 110-4',
                                    'cafe' => 'M13 10V3L4 14h7v7l9-11h-7z',
                                    'bar' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                    default => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                                };
                            @endphp
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
                            </svg>
                        </div>
                        <span class="mt-2 text-xs font-bold uppercase tracking-wider {{ request('category') == $category->slug ? 'text-black' : 'text-slate-400' }}">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Restaurants Grid -->
    <section class="py-16 bg-[#FAFAFA] min-h-[60vh]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($restaurants as $restaurant)
                    <div class="bg-white rounded-[2rem] overflow-hidden border border-slate-100 hover:shadow-xl transition-all duration-300 group">
                        <div class="relative h-48">
                            @if($restaurant->cover_image)
                                <img src="{{ Storage::url($restaurant->cover_image) }}" alt="{{ $restaurant->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                                    <span class="text-slate-300 text-4xl font-black">{{ substr($restaurant->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-black uppercase text-[#FF4F18]">
                                20-30 min
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="font-bold text-lg text-slate-900 mb-1 line-clamp-1">{{ $restaurant->name }}</h3>
                            <div class="flex items-center text-slate-400 text-sm mb-4">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    4.8 (500+)
                                </span>
                                <span class="mx-2">•</span>
                                <span>{{ $restaurant->businessCategory?->name }}</span>
                            </div>
                            <a href="{{ $restaurant->domain ? 'https://' . $restaurant->domain . '/menu' : 'https://' . $restaurant->slug . '.' . config('tenancy.central_domains')[0] . '/menu' }}" 
                               class="block w-full text-center py-3 bg-slate-900 text-white rounded-2xl font-bold hover:bg-[#FF4F18] transition-colors">
                                Order Now
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-16">
                {{ $restaurants->links() }}
            </div>
        </div>
    </section>
@endsection
