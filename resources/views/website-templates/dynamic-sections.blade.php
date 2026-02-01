@php
    $settings = $website->content['settings'] ?? [];
    $primaryColor = $settings['primary_color'] ?? '#0B132B';
    $borderRadius = $settings['border_radius'] ?? 'lg';
    $fontFamily = $settings['font_family'] ?? 'Outfit';

    $radiusMap = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        '2xl' => 'rounded-2xl',
        '3xl' => 'rounded-3xl',
        '4xl' => 'rounded-[2rem]',
        '5xl' => 'rounded-[2.5rem]',
        '6xl' => 'rounded-[3rem]',
        'full' => 'rounded-full',
    ];

    $radiusClass = $radiusMap[$borderRadius] ?? 'rounded-lg';
    // For larger containers, we might want a slightly larger radius proportionally
    $containerRadiusClass = $radiusMap[$borderRadius === 'none' ? 'none' : ($borderRadius === 'full' ? 'full' : '3xl')] ?? 'rounded-[2rem]';
    
    $googleFonts = [
        'Inter' => 'Inter:wght@300;400;500;600;700',
        'Outfit' => 'Outfit:wght@300;400;600;700',
        'Playfair Display' => 'Playfair+Display:wght@400;700',
        'Montserrat' => 'Montserrat:wght@300;400;600;700',
    ];
    $fontUrl = $googleFonts[$fontFamily] ?? $googleFonts['Outfit'];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $website->content['hero']['title'] ?? tenant('name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f4ff',
                            100: '#e1e9ff',
                            200: '#c3d3ff',
                            300: '#a5bdff',
                            400: '#87a7ff',
                            500: '{{ $primaryColor }}',
                            600: '{{ $primaryColor }}',
                            700: '#091024',
                            800: '#070d1d',
                            900: '#050a16',
                        }
                    },
                    fontFamily: {
                        sans: ['{{ $fontFamily }}', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family={{ $fontUrl }}&display=swap" rel="stylesheet">
    <style>
        body { font-family: '{{ $fontFamily }}', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 scroll-smooth">

    @foreach($website->content['sections'] ?? [] as $section)
        @php $data = $section['data'] ?? $section['content'] ?? []; @endphp
        
        @switch($section['type'])
            @case('nav')
                <nav class="fixed top-0 left-0 right-0 z-50 glass border-b border-gray-200/50">
                    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                        <a href="#" class="text-2xl font-bold text-primary-600 tracking-tight">{{ $data['logo_text'] ?? tenant('name') }}</a>
                        
                        <div class="hidden md:flex items-center gap-10">
                            @foreach($data['links'] ?? [] as $link)
                                <a href="{{ $link['url'] ?? '#' }}" class="text-sm font-semibold text-gray-600 hover:text-primary-600 transition-colors uppercase tracking-wider">
                                    {{ $link['label'] ?? '' }}
                                </a>
                            @endforeach
                            <a href="/reservations" class="bg-primary-600 text-white px-6 py-2.5 rounded-full text-sm font-bold hover:shadow-lg transition-all transform hover:scale-105">
                                Book Now
                            </a>
                        </div>
                    </div>
                </nav>
                <div class="h-20"></div> {{-- Spacer for fixed nav --}}
                @break

                <section id="{{ $section['id'] ?? 'hero' }}" class="relative min-h-[85vh] flex items-center justify-center overflow-hidden m-4 {{ $containerRadiusClass }}">
                    @if($data['background_image'] ?? null)
                        <img src="{{ \App\Helpers\StorageHelper::getUrl($data['background_image']) }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                    @endif
                    <div class="absolute inset-0 bg-linear-to-b from-black/70 via-black/40 to-black/70"></div>
                    
                    <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
                        <h1 class="text-5xl md:text-8xl font-bold text-white mb-8 drop-shadow-2xl leading-tight">
                            {{ $data['title'] ?? 'Welcome' }}
                        </h1>
                        <p class="text-xl md:text-2xl text-gray-200 mb-12 max-w-2xl mx-auto drop-shadow-lg font-light">
                            {{ $data['subtitle'] ?? '' }}
                        </p>
                        <div class="flex flex-col sm:flex-row gap-6 justify-center">
                            @if($data['button_text'] ?? null)
                                <a href="{{ $data['button_link'] ?? '#' }}" class="inline-block bg-primary-600 text-white px-12 py-5 {{ $radiusClass }} font-bold text-lg hover:bg-primary-700 transition-all transform hover:scale-105 shadow-2xl shadow-primary-600/30">
                                    {{ $data['button_text'] }}
                                </a>
                            @endif
                            @if($data['button_2_text'] ?? null)
                                <a href="{{ $data['button_2_link'] ?? '#' }}" class="inline-block bg-white text-black px-12 py-5 {{ $radiusClass }} font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-2xl">
                                    {{ $data['button_2_text'] }}
                                </a>
                            @elseif(!($data['button_text'] ?? null))
                                <a href="/menu" class="inline-block bg-white text-black px-12 py-5 {{ $radiusClass }} font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105 shadow-2xl">
                                    Order Now
                                </a>
                            @endif
                        </div>
                    </div>
                </section>
                @break

            @case('about')
                <section id="{{ $section['id'] ?? 'about' }}" class="py-32 bg-white">
                    <div class="max-w-7xl mx-auto px-6 flex flex-col lg:flex-row items-center gap-20">
                        <div class="flex-1 order-2 lg:order-1">
                            <span class="text-primary-600 font-bold uppercase tracking-widest text-sm mb-4 block italic">Our Story</span>
                            <h2 class="text-5xl md:text-6xl font-bold mb-10 text-gray-900 leading-tight">{{ $data['title'] ?? 'Our Story' }}</h2>
                            <div class="text-xl text-gray-600 leading-relaxed mb-10 font-light">
                                {!! nl2br(e($data['text'] ?? '')) !!}
                            </div>
                        </div>
                        @if($data['image'] ?? null)
                            <div class="flex-1 order-1 lg:order-2">
                                <div class="relative">
                                    <div class="absolute -inset-4 bg-primary-50 {{ $containerRadiusClass }} -rotate-3 transition-transform group-hover:rotate-0"></div>
                                    <img src="{{ \App\Helpers\StorageHelper::getUrl($data['image']) }}" class="relative {{ $containerRadiusClass }} shadow-2xl w-full h-[500px] object-cover">
                                </div>
                            </div>
                        @else
                             <div class="flex-1 order-1 lg:order-2">
                                <div class="relative">
                                    <div class="absolute -inset-4 bg-primary-50 {{ $containerRadiusClass }} -rotate-3 transition-transform group-hover:rotate-0"></div>
                                    <img src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?q=80&w=2070" class="relative {{ $containerRadiusClass }} shadow-2xl w-full h-[500px] object-cover">
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
                @break

            @case('menu')
                @php
                    $menuItems = ($data['source'] ?? 'manual') === 'database' 
                        ? \App\Models\MenuItem::where('is_active', true)->orderBy('sort_order')->take(6)->get()
                        : collect($data['items'] ?? []);
                @endphp
                <section id="{{ $section['id'] ?? 'menu' }}" class="py-32 bg-gray-50 m-4 {{ $containerRadiusClass }}">
                    <div class="max-w-7xl mx-auto px-6">
                        <div class="text-center mb-20">
                            <span class="text-primary-600 font-bold uppercase tracking-widest text-sm mb-4 block italic">Our Menu</span>
                            <h2 class="text-5xl md:text-6xl font-bold mb-6 text-gray-900 leading-tight">{{ $data['title'] ?? 'Signature Dishes' }}</h2>
                            <div class="w-24 h-1.5 bg-primary-600 mx-auto rounded-full"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                            @foreach($menuItems as $item)
                                @php 
                                    $itemName = is_array($item) ? ($item['name'] ?? '') : $item->name;
                                    $itemDesc = is_array($item) ? ($item['description'] ?? '') : $item->description;
                                    $itemPrice = is_array($item) ? ($item['price'] ?? '') : $item->base_price;
                                    $itemImage = is_array($item) ? ($item['image'] ?? null) : $item->image_path;
                                @endphp
                                <div class="bg-white p-6 {{ $containerRadiusClass }} shadow-sm hover:shadow-2xl transition-all duration-500 group border border-gray-100">
                                    <div class="aspect-4/3 mb-8 overflow-hidden {{ $radiusClass }} relative">
                                        @if($itemImage)
                                            <img src="{{ \App\Helpers\StorageHelper::getUrl($itemImage) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                        @else
                                            <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=2070" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                        @endif
                                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-4 py-2 rounded-full font-bold text-primary-600 shadow-lg">
                                            {{ is_numeric($itemPrice) ? '$' . number_format($itemPrice, 2) : $itemPrice }}
                                        </div>
                                    </div>
                                    <h3 class="text-2xl font-bold mb-4 px-2">{{ $itemName }}</h3>
                                    <p class="text-gray-500 mb-8 px-2 font-light leading-relaxed">{{ Str::limit($itemDesc, 100) }}</p>
                                    <div class="flex items-center justify-between px-2 pb-2">
                                        <a href="/menu" class="bg-gray-900 text-white px-8 py-3 {{ $radiusClass }} font-bold hover:bg-primary-600 transition-colors shadow-lg">
                                            Order Now
                                        </a>
                                        <div class="w-12 h-12 {{ $radiusClass }} bg-primary-50 flex items-center justify-center text-primary-600 cursor-pointer hover:bg-primary-600 hover:text-white transition-all">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
                @break

            @case('features')
                <section id="{{ $section['id'] ?? 'features' }}" class="py-32 bg-white">
                    <div class="max-w-7xl mx-auto px-6">
                        <div class="text-center mb-24">
                            <h2 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight">{{ $data['title'] ?? 'Our Services' }}</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                            @foreach($data['items'] ?? [] as $item)
                                <div class="bg-gray-50 p-12 {{ $containerRadiusClass }} border border-transparent hover:border-primary-100 hover:bg-white hover:shadow-2xl transition-all duration-500 group">
                                    <div class="w-16 h-16 bg-primary-600 text-white {{ $radiusClass }} flex items-center justify-center mb-10 shadow-xl shadow-primary-600/20 group-hover:scale-110 transition-transform">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <h3 class="text-2xl font-bold mb-6 text-gray-900 uppercase tracking-tight">{{ $item['title'] ?? '' }}</h3>
                                    <p class="text-gray-500 text-lg leading-relaxed font-light">{{ $item['text'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
                @break

            @case('contact')
                <section id="{{ $section['id'] ?? 'contact' }}" class="py-32 bg-gray-900 m-4 {{ $containerRadiusClass }} text-white overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-primary-600/10 rounded-full -mr-48 -mt-48 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary-600/10 rounded-full -ml-48 -mb-48 blur-3xl"></div>
                    
                    <div class="max-w-7xl mx-auto px-6 relative z-10">
                        <div class="grid lg:grid-cols-2 gap-24 items-center">
                            <div>
                                <h2 class="text-5xl md:text-7xl font-bold mb-12 leading-tight">Let's Connect</h2>
                                <p class="text-xl text-gray-400 mb-12 font-light leading-relaxed">Visit us today or get in touch for reservations and private events.</p>
                                <div class="space-y-10">
                                    <div class="flex items-center gap-8">
                                        <div class="w-14 h-14 {{ $radiusClass }} bg-white/5 border border-white/10 flex items-center justify-center text-primary-500">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Our Location</h4>
                                            <p class="text-2xl font-medium">{{ $data['address'] ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-8">
                                        <div class="w-14 h-14 {{ $radiusClass }} bg-white/5 border border-white/10 flex items-center justify-center text-primary-500">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Phone Number</h4>
                                            <p class="text-2xl font-medium">{{ $data['phone'] ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white/5 backdrop-blur-xl p-12 {{ $containerRadiusClass }} border border-white/10">
                                <h3 class="text-3xl font-bold mb-8">Send a Message</h3>
                                <form class="space-y-6">
                                    <input type="text" placeholder="Name" class="w-full bg-white/5 border border-white/10 {{ $radiusClass }} px-6 py-4 focus:border-primary-500 focus:outline-none transition">
                                    <input type="email" placeholder="Email" class="w-full bg-white/5 border border-white/10 {{ $radiusClass }} px-6 py-4 focus:border-primary-500 focus:outline-none transition">
                                    <textarea placeholder="Message" rows="4" class="w-full bg-white/5 border border-white/10 {{ $radiusClass }} px-6 py-4 focus:border-primary-500 focus:outline-none transition"></textarea>
                                    <button class="w-full bg-primary-600 py-5 {{ $radiusClass }} font-bold text-lg hover:bg-primary-700 transition shadow-xl shadow-primary-600/20">Send Now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
                @break

            @case('footer')
                <footer class="py-12 bg-gray-900 text-white">
                    <div class="max-w-6xl mx-auto px-6 text-center">
                        <p class="text-gray-400 opacity-60">{{ $data['text'] ?? '' }}</p>
                    </div>
                </footer>
                @break
        @endswitch
    @endforeach

</body>
</html>
