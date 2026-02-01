@php
    $settings = $website->content['settings'] ?? [];
    $primaryColor = $settings['primary_color'] ?? '#6366f1'; // Default indigo
    $borderRadius = $settings['border_radius'] ?? 'lg';
    $fontFamily = $settings['font_family'] ?? 'Inter';

    $radiusMap = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        '2xl' => 'rounded-2xl',
        '3xl' => 'rounded-3xl',
        'full' => 'rounded-full',
    ];

    $radiusClass = $radiusMap[$borderRadius] ?? 'rounded-lg';

    $googleFonts = [
        'Inter' => 'Inter:wght@400;500;600;700',
        'Outfit' => 'Outfit:wght@400;600;700',
        'Playfair Display' => 'Playfair+Display:wght@400;700',
        'Montserrat' => 'Montserrat:wght@400;600;700',
    ];
    $fontUrl = $googleFonts[$fontFamily] ?? $googleFonts['Inter'];
@endphp

<div class="min-h-screen bg-white text-gray-900 font-sans" style="font-family: '{{ $fontFamily }}', sans-serif;">
    <style>
        :root {
            --primary-color:
                {{ $primaryColor }}
            ;
        }

        .bg-primary {
            background-color:
                {{ $primaryColor }}
            ;
        }

        .text-primary {
            color:
                {{ $primaryColor }}
            ;
        }

        .border-primary {
            border-color:
                {{ $primaryColor }}
            ;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family={{ $fontUrl }}&display=swap" rel="stylesheet">

    <!-- Hero / Header -->
    <header class="relative h-72 bg-cover bg-center"
        style="background-image: url('{{ $website->content['menu_hero_image'] ?? 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80' }}');">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-[2px]"></div>
        <div
            class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center items-center text-center">
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight mb-4 uppercase">
                {{ $website->content['business_name'] ?? tenant('name') }}
            </h1>
            <p class="text-lg text-gray-200 max-w-2xl italic">
                {{ $website->content['menu_subtitle'] ?? 'Experience culinary excellence with our curated selection of dishes.' }}
            </p>
        </div>
    </header>

    <!-- Navigation / Categories -->
    <div
        class="sticky top-0 z-10 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex overflow-x-auto space-x-8 py-4 no-scrollbar">
                @foreach($categories as $category)
                            <button wire:click="$set('activeCategoryId', {{ $category->id }})" class="whitespace-nowrap px-1 py-2 border-b-2 text-sm font-medium transition-colors
                                                                        {{ $activeCategoryId == $category->id
                    ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
                    : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' 
                                                                        }}">
                                {{ $category->name }}
                            </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Menu Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        @forelse($categories as $category)
            @if($category->menuItems->count() > 0)
                <div id="category-{{ $category->id }}"
                    class="mb-12 scroll-mt-24 {{ $activeCategoryId && $activeCategoryId != $category->id ? 'hidden' : '' }}">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white flex items-center">
                        <span class="w-1 h-8 bg-indigo-500 rounded-full mr-3"
                            style="background-color: {{ $primaryColor }}"></span>
                        {{ $category->name }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($category->menuItems as $item)
                            <div
                                class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden">
                                <!-- Image -->
                                <div class="aspect-w-16 aspect-h-9 bg-gray-200 h-48 w-full overflow-hidden">
                                    @if($item->image_path)
                                        <img src="{{ \App\Helpers\StorageHelper::getUrl($item->image_path) }}" alt="{{ $item->name }}"
                                            class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="flex items-center justify-center h-full text-gray-400">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3
                                            class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-primary transition-colors">
                                            {{ $item->name }}
                                        </h3>
                                        <span class="font-bold text-primary dark:text-primary">
                                            ${{ number_format($item->base_price, 2) }}
                                        </span>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                        {{ $item->description }}
                                    </p>

                                    <button wire:click="addToCart({{ $item->id }})"
                                        class="w-full py-2 px-4 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Add to Order
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @empty
            <div class="text-center py-20" data-aos="fade-up">
                <div
                    class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400 mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Menu Coming Soon</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">We're currently updating our menu with
                    delicious offerings. Please check back later!</p>
                <a href="/"
                    class="inline-block mt-8 text-sm font-bold text-primary uppercase tracking-widest hover:underline">←
                    Back to Home</a>
            </div>
        @endforelse
    </main>

    </main>
</div>