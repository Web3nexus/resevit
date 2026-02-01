@php
    $settings = $website->content['settings'] ?? [];
    $primaryColor = $settings['primary_color'] ?? '#FF2E5B';
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
    $containerRadiusClass = $radiusMap[$borderRadius === 'none' ? 'none' : 'sm'] ?? 'rounded-sm';
    
    $googleFonts = [
        'Inter' => 'Inter:wght@300;400;500;600;700',
        'Outfit' => 'Outfit:wght@300;400;600;700',
        'Playfair Display' => 'Playfair+Display:wght@400;700',
        'Montserrat' => 'Montserrat:wght@300;400;600;700',
    ];
    $fontUrl = $googleFonts[$fontFamily] ?? $googleFonts['Inter'];
@endphp
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $website->content['business_name'] ?? tenant('name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://fonts.googleapis.com/css2?family={{ $fontUrl }}&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['{{ $fontFamily }}', 'sans-serif'],
                    },
                    colors: {
                        primary: '{{ $primaryColor }}',
                        dark: '#0A0A0A',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: '{{ $fontFamily }}', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="font-sans text-dark antialiased bg-white" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navigation -->
    <nav :class="scrolled ? 'bg-white shadow-lg py-3' : 'glass-nav py-5'" class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <img src="{{ \App\Helpers\StorageHelper::getUrl($website->content['logo'] ?? '') }}" class="h-8 w-auto" alt="Logo" onerror="this.style.display='none'">
                <span :class="scrolled ? 'text-dark' : 'text-white'" class="text-xl font-bold font-serif">{{ $website->content['business_name'] ?? tenant('name') }}</span>
            </a>

            <div class="hidden lg:flex items-center space-x-10">
                @foreach(['Home', 'About', 'Menu', 'Experts', 'News', 'Contact'] as $link)
                    <a href="#{{ strtolower($link) }}" :class="scrolled ? 'text-gray-600 hover:text-primary' : 'text-white/80 hover:text-white'" class="text-sm font-medium transition italic">{{ $link }}</a>
                @endforeach
            </div>

            <div class="flex items-center gap-6">
                <a href="/reservations" class="bg-primary text-white px-6 py-2.5 {{ $containerRadiusClass }} font-bold text-sm hover:bg-opacity-90 transition transform hover:-translate-y-0.5 shadow-lg shadow-primary/20">
                    BOOK NOW
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative h-screen bg-dark flex items-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ \App\Helpers\StorageHelper::getUrl($content['hero_image'] ?? 'design/Home-1.png') }}" class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/40 to-transparent"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-3xl" data-aos="fade-right">
                <h1 class="text-white text-5xl md:text-8xl font-serif font-bold leading-tight mb-8">
                    {{ $website->content['hero_title'] ?? 'The Perfect Space to Enjoy Fantastic Food' }}
                </h1>
                <p class="text-white/70 text-lg md:text-xl mb-10 max-w-xl leading-relaxed">
                    {{ $website->content['hero_subtitle'] ?? 'Experience culinary excellence with our master chefs who prepare every dish with passion and the finest local ingredients.' }}
                </p>
                <div class="flex items-center gap-8">
                    <a href="/reservations" class="bg-primary text-white px-8 py-4 {{ $containerRadiusClass }} font-bold tracking-widest hover:bg-opacity-90 transition">BOOK NOW</a>
                    <a href="/menu" class="flex items-center gap-4 group">
                        <span class="w-14 h-14 rounded-full border border-white/30 flex items-center justify-center text-white group-hover:bg-primary group-hover:border-primary transition duration-300">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </span>
                        <span class="text-white font-bold tracking-widest text-sm uppercase">Order Now</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Social Sidebar -->
        <div class="absolute right-8 top-1/2 -translate-y-1/2 hidden lg:flex flex-col gap-8 items-center">
            <div class="w-px h-24 bg-white/20"></div>
            <a href="#" class="text-white/40 hover:text-white transition rotate-90 my-4 text-xs tracking-widest uppercase">Instagram</a>
            <a href="#" class="text-white/40 hover:text-white transition rotate-90 my-4 text-xs tracking-widest uppercase">Facebook</a>
            <a href="#" class="text-white/40 hover:text-white transition rotate-90 my-4 text-xs tracking-widest uppercase">Twitter</a>
            <div class="w-px h-24 bg-white/20"></div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-32 bg-white overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="relative" data-aos="fade-right">
                    <img src="{{ \App\Helpers\StorageHelper::getUrl($website->content['about_image_1'] ?? 'https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?q=80&w=2070') }}" class="w-full h-[600px] object-cover {{ $containerRadiusClass }} shadow-2xl">
                    <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-primary p-4 {{ $containerRadiusClass }} hidden md:block">
                        <img src="{{ \App\Helpers\StorageHelper::getUrl($website->content['about_image_2'] ?? 'https://images.unsplash.com/photo-1470337458703-46ad1756a187?q=80&w=2069') }}" class="w-full h-full object-cover">
                    </div>
                </div>

                <div data-aos="fade-left">
                    <span class="text-primary font-bold tracking-widest uppercase text-sm mb-4 block">About Our Business</span>
                    <h2 class="text-4xl md:text-6xl font-serif font-bold mb-8 leading-tight">
                        {{ $website->content['about_title'] ?? 'Excellence in every detail' }}
                    </h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-10">
                        {{ $website->content['about_description'] ?? 'We provide a unique experience where quality meets passion. Our team carefully selects the best materials and ingredients to create unforgettable results for you.' }}
                    </p>

                    <div class="flex items-center gap-6 mb-12">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" class="w-16 h-16 rounded-full object-cover border-2 border-primary/20">
                        <div>
                            <h4 class="font-serif font-bold text-xl">{{ tenant('owner')?->name ?? 'William James' }}</h4>
                            <span class="text-gray-400 text-sm italic">Founder & CEO</span>
                        </div>
                    </div>

                    <div class="flex gap-8">
                        <a href="/reservations" class="bg-primary text-white px-10 py-4 {{ $radiusClass }} font-bold tracking-widest hover:bg-opacity-90 transition shadow-xl shadow-primary/20">BOOK NOW</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Banner -->
    <section class="py-20 bg-primary text-white">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12 text-center">
                @foreach($content['services'] ?? [
                    ['icon' => '🍷', 'label' => 'Drinks'],
                    ['icon' => '🍖', 'label' => 'Steak'],
                    ['icon' => '☕', 'label' => 'Coffee'],
                    ['icon' => '🥗', 'label' => 'Salads']
                ] as $service)
                    <div class="flex flex-col items-center gap-4 group hover:-translate-y-2 transition duration-300">
                        <div class="w-20 h-20 rounded-full bg-white/10 flex items-center justify-center text-4xl group-hover:bg-white/20 transition">
                            {{ $service['icon'] }}
                        </div>
                        <span class="font-bold tracking-widest uppercase text-sm">{{ $service['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="py-32 bg-gray-50 relative overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="text-center mb-20" data-aos="fade-up">
                <span class="text-primary font-bold tracking-widest uppercase text-sm mb-4 block">Our Specialties</span>
                <h2 class="text-4xl md:text-6xl font-serif font-bold mb-6">Discover Menu</h2>
                <div class="w-24 h-1 bg-primary mx-auto"></div>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                @php
                    $h1MenuItems = \App\Models\MenuItem::where('is_active', true)->orderBy('sort_order')->take(2)->get();
                @endphp
                @if($h1MenuItems->isNotEmpty())
                    @foreach($h1MenuItems as $item)
                        <div class="relative h-[500px] group overflow-hidden {{ $containerRadiusClass }} cursor-pointer" data-aos="zoom-in">
                            <img src="{{ \App\Helpers\StorageHelper::getUrl($item->image_path ?? 'https://images.unsplash.com/photo-1593030761757-71fae45fa0e7?q=80&w=2070') }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                            <div class="absolute inset-0 bg-dark/40 group-hover:bg-dark/20 transition duration-500"></div>
                            <div class="absolute inset-0 flex flex-col justify-end p-12">
                                <h3 class="text-white text-4xl font-serif font-bold mb-6">{{ $item->name }}</h3>
                                <button @click="$dispatch('add-to-cart', { menuItemId: {{ $item->id }} })" 
                                        class="w-fit bg-primary text-white p-4 h-12 flex items-center gap-4 font-bold {{ $radiusClass }} border-2 border-primary hover:bg-transparent transition duration-300 uppercase">
                                    ORDER NOW
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach(['Special Dishes', 'Tasty Drinks'] as $title)
                        <div class="relative h-[500px] group overflow-hidden {{ $containerRadiusClass }} cursor-pointer" data-aos="zoom-in">
                            <img src="https://images.unsplash.com/photo-1593030761757-71fae45fa0e7?q=80&w=2070" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                            <div class="absolute inset-0 bg-dark/40 group-hover:bg-dark/20 transition duration-500"></div>
                            <div class="absolute inset-0 flex flex-col justify-end p-12">
                                <h3 class="text-white text-4xl font-serif font-bold mb-6">{{ $title }}</h3>
                                <a href="/menu" class="w-fit bg-primary text-white p-4 h-12 flex items-center gap-4 font-bold {{ $radiusClass }} border-2 border-primary hover:bg-transparent transition duration-300">
                                    VIEW MENU
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- Booking Section -->
    <section id="booking" class="py-32 bg-white">
        <div class="container mx-auto px-6">
            <div class="bg-primary {{ $containerRadiusClass }} p-12 md:p-20 relative overflow-hidden shadow-2xl">
                <!-- Decoration -->
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -mr-32 -mt-32"></div>

                <div class="grid lg:grid-cols-5 items-center gap-12 relative z-10">
                    <div class="lg:col-span-2">
                        <h2 class="text-white text-5xl font-serif font-bold leading-tight mb-8 uppercase">Reserve A Table</h2>
                        <p class="text-white/70 mb-8 italic">Choose your preferred date and time to experience our fantastic cuisine.</p>
                    </div>
                    <div class="lg:col-span-3">
                        <div class="grid md:grid-cols-2 gap-6">
                            <input type="text" placeholder="Full Name" class="bg-white/10 border border-white/20 text-white placeholder-white/50 px-6 py-4 {{ $radiusClass }} focus:bg-white/20 focus:outline-none transition">
                            <input type="email" placeholder="Email Address" class="bg-white/10 border border-white/20 text-white placeholder-white/50 px-6 py-4 {{ $radiusClass }} focus:bg-white/20 focus:outline-none transition">
                            <input type="date" class="bg-white/10 border border-white/20 text-white placeholder-white/50 px-6 py-4 {{ $radiusClass }} focus:bg-white/20 focus:outline-none transition">
                            <select class="bg-white/10 border border-white/20 text-white px-6 py-4 {{ $radiusClass }} focus:bg-white/20 focus:outline-none transition">
                                <option class="text-dark">2 People</option>
                                <option class="text-dark">4 People</option>
                                <option class="text-dark">6+ People</option>
                            </select>
                            <a href="/reservations" class="md:col-span-2 bg-white text-primary text-center font-bold py-4 {{ $radiusClass }} hover:bg-gray-100 transition tracking-widest uppercase">CONFIRM BOOKING</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Dishes Section -->
    <section class="py-32 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-20" data-aos="fade-up">
                <span class="text-primary font-bold tracking-widest uppercase text-sm mb-4 block">Recommendation</span>
                <h2 class="text-4xl md:text-6xl font-serif font-bold mb-10">Featured Dishes</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                @php
                    $featuredItems = \App\Models\MenuItem::where('is_active', true)->where('is_available', true)->take(3)->get();
                @endphp
                @if($featuredItems->isNotEmpty())
                    @foreach($featuredItems as $item)
                        <div class="bg-white p-8 {{ $radiusClass }} shadow-sm hover:shadow-xl transition transform hover:-translate-y-2 group text-center">
                            <div class="aspect-square mb-8 overflow-hidden {{ $radiusClass }} relative">
                                <img src="{{ \App\Helpers\StorageHelper::getUrl($item->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            </div>
                            <h4 class="font-serif font-bold text-2xl mb-4 uppercase">{{ $item->name }}</h4>
                            <div class="text-primary font-bold text-xl mb-6">${{ number_format($item->base_price, 2) }}</div>
                            <div class="flex justify-center">
                                <a href="/menu" class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center hover:bg-dark transition shadow-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach(['Classic Burger', 'Spicy Pasta', 'Grilled Salmon'] as $name)
                        <div class="bg-white p-8 {{ $radiusClass }} shadow-sm hover:shadow-xl transition transform hover:-translate-y-2 group text-center">
                            <div class="aspect-square mb-8 overflow-hidden {{ $radiusClass }} relative">
                                <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1780" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            </div>
                            <h4 class="font-serif font-bold text-2xl mb-4 uppercase">{{ $name }}</h4>
                            <div class="text-primary font-bold text-xl mb-6">$19.99</div>
                            <div class="flex justify-center">
                                <a href="/menu" class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center hover:bg-dark transition shadow-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-32 bg-white relative overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div data-aos="fade-right">
                    <span class="text-primary font-bold tracking-widest uppercase text-sm mb-4 block">Testimonials</span>
                    <h2 class="text-4xl md:text-5xl font-serif font-bold mb-10 leading-tight">Our Customer Feedbacks</h2>
                    <blockquote class="text-gray-500 text-xl leading-relaxed italic mb-12 relative">
                        <span class="text-8xl text-primary opacity-10 absolute -top-10 -left-10 font-serif">"</span>
                        "The atmosphere was amazing and the food was beyond my expectations. Every dish had a unique flavor profile that truly showcased the talent in the kitchen. I'll definitely be back soon!"
                    </blockquote>
                    <button class="bg-yellow-400 text-dark p-4 rounded-full shadow-lg hover:shadow-xl transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 11.5l3-3-3-3V7.5L8.5 11l3.5 3.5V11.5z"/></svg>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-6" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1522336572018-3488737cc530?q=80&w=1964" class="w-full h-80 object-cover rounded-sm mt-12">
                    <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=1976" class="w-full h-80 object-cover rounded-sm">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?q=80&w=2070" class="col-span-2 w-full h-[400px] object-cover rounded-sm -mt-20">
                </div>
            </div>
        </div>
    </section>

    <!-- Experts Section -->
    <section id="experts" class="py-32 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-20" data-aos="fade-up">
                <span class="text-primary font-bold tracking-widest uppercase text-sm mb-4 block">Our Team</span>
                <h2 class="text-4xl md:text-6xl font-serif font-bold mb-6">Meet Our Experts</h2>
                <div class="w-24 h-1 bg-primary mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                @foreach($content['team'] ?? [
                    ['name' => 'Thomas Meisner', 'role' => 'Master Chef', 'image' => 'https://randomuser.me/api/portraits/men/44.jpg'],
                    ['name' => 'James Jhon', 'role' => 'Senior Chef', 'image' => 'https://randomuser.me/api/portraits/men/46.jpg'],
                    ['name' => 'Ravini Minali', 'role' => 'Pastry Chef', 'image' => 'https://randomuser.me/api/portraits/women/44.jpg']
                ] as $member)
                    <div class="text-center group" data-aos="fade-up">
                        <div class="relative w-72 h-72 mx-auto mb-8">
                            <div class="absolute inset-0 border-2 border-primary/20 rounded-full scale-110 group-hover:scale-100 transition duration-500"></div>
                            <img src="{{ $member['image'] }}" class="w-full h-full object-cover rounded-full shadow-xl">
                        </div>
                        <h4 class="font-serif font-bold text-2xl mb-2">{{ $member['name'] }}</h4>
                        <span class="text-primary text-sm font-bold italic tracking-widest uppercase">{{ $member['role'] }}</span>
                        <div class="flex justify-center gap-4 mt-6">
                            @foreach(['FB', 'TW', 'IG'] as $social)
                                <a href="#" class="w-10 h-10 border border-gray-200 rounded-full flex items-center justify-center text-gray-400 hover:bg-primary hover:border-primary hover:text-white transition transform hover:-translate-y-1 text-xs font-bold">{{ $social }}</a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Mobile App Section -->
    <section class="py-32 bg-white">
        <div class="container mx-auto px-6">
            <div class="bg-gray-100 rounded-sm p-12 md:p-20 grid lg:grid-cols-2 items-center gap-20 shadow-inner">
                <div data-aos="fade-right">
                    <span class="text-primary font-bold tracking-widest uppercase text-sm mb-4 block">Stay Connected</span>
                    <h2 class="text-4xl md:text-5xl font-serif font-bold mb-8 leading-tight">Manage Your Restaurant Anytime anywhere!</h2>
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-center gap-3 font-medium text-gray-600">
                            <span class="text-primary">✓</span> Real-time order tracking
                        </li>
                        <li class="flex items-center gap-3 font-medium text-gray-600">
                            <span class="text-primary">✓</span> Exclusive in-app offers
                        </li>
                    </ul>
                    <div class="flex gap-4">
                        <button class="bg-dark text-white px-8 py-3 rounded-sm font-bold tracking-widest text-xs hover:bg-opacity-90 flex items-center gap-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.1 2.48-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.36 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                            APP STORE
                        </button>
                        <button class="bg-dark text-white px-8 py-3 rounded-sm font-bold tracking-widest text-xs hover:bg-opacity-90 flex items-center gap-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M3.609 1.814L13.792 12 3.61 22.186c-.18.18-.31.41-.31.67 0 .51.41.92.92.92.19 0 .37-.06.51-.16l15.208-8.814c.23-.13.38-.38.38-.66s-.15-.53-.38-.66L5.22.82c-.14-.1-.32-.16-.51-.16-.51 0-.92.41-.92.92 0 .26.13.49.31.67z"/></svg>
                            PLAY STORE
                        </button>
                    </div>
                </div>
                <div class="relative" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1512428559083-a401a30c9550?q=80&w=2070" class="w-full rounded-sm shadow-2xl relative z-10">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section id="news" class="py-32 bg-gray-50">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-6xl font-serif font-bold mb-20" data-aos="fade-up">Recent News</h2>

            <div class="grid md:grid-cols-2 gap-12 max-w-5xl mx-auto">
                @foreach($content['news'] ?? [
                    ['title' => 'Creamy Chicken with milk', 'date' => 'Oct 15, 2024', 'author' => 'Chef James', 'image' => 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?q=80&w=2070'],
                    ['title' => 'As Fry Salmon', 'date' => 'Oct 12, 2024', 'author' => 'Chef Maria', 'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?q=80&w=2070']
                ] as $post)
                    <div class="flex gap-8 items-center bg-white p-6 rounded-sm shadow-sm hover:shadow-xl transition text-left group" data-aos="fade-up">
                        <div class="w-40 h-40 shrink-0 overflow-hidden rounded-sm">
                            <img src="{{ $post['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        <div>
                            <span class="text-primary font-bold text-xs tracking-widest uppercase mb-2 block italic">{{ $post['date'] }}</span>
                            <h4 class="font-serif font-bold text-2xl mb-4 leading-tight group-hover:text-primary transition">{{ $post['title'] }}</h4>
                            <div class="flex items-center gap-3">
                                <img src="https://randomuser.me/api/portraits/thumb/men/1.jpg" class="w-6 h-6 rounded-full">
                                <span class="text-gray-400 text-xs italic">By {{ $post['author'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Instagram Feed -->
    <section class="py-32 bg-white">
        <div class="container mx-auto px-6 text-center">
            <h3 class="text-2xl font-serif font-bold mb-10">Follow {{ $content['instagram_handle'] ?? '@resevit' }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($content['instagram_feed'] ?? [
                    'https://images.unsplash.com/photo-1543353071-873f17a7a088',
                    'https://images.unsplash.com/photo-1543353071-8d0295173a21',
                    'https://images.unsplash.com/photo-1543352658-927cf1777585',
                    'https://images.unsplash.com/photo-1543352658-05256e6d191d',
                    'https://images.unsplash.com/photo-1543352658-f5d6067b931a',
                    'https://images.unsplash.com/photo-1543352658-37c4468f773b'
                ] as $img)
                    <div class="aspect-square relative group overflow-hidden rounded-sm cursor-pointer">
                        <img src="{{ $img }}?q=80&w=1000&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-primary/60 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center text-white">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.072 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 pt-24 pb-12 text-white overflow-hidden relative">
         <div class="absolute top-0 right-0 w-96 h-96 bg-primary/10 rounded-full -mr-48 -mt-48 blur-3xl"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid md:grid-cols-4 gap-20 mb-20">
                <div class="md:col-span-2">
                    <div class="bg-white/5 backdrop-blur-xl p-12 {{ $containerRadiusClass }} border border-white/10 mb-10">
                        <a href="#" class="text-4xl font-serif font-bold mb-8 block">{{ $website->content['business_name'] ?? tenant('name') }}</a>
                        <p class="leading-relaxed mb-6 text-gray-400 font-light">{{ $website->content['footer_text'] ?? 'Experience culinary excellence with our curated specialties. Quality meets passion in every dish we serve.' }}</p>
                        <div class="flex gap-4">
                            @foreach(['FB', 'TW', 'IG'] as $social)
                                <a href="#" class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center hover:bg-primary hover:border-primary transition duration-300">{{ $social }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold tracking-widest uppercase text-sm mb-10 text-gray-500">Quick Links</h4>
                    <ul class="space-y-4">
                        @foreach(['Home', 'About', 'Menu', 'Experts'] as $item)
                            <li><a href="#{{ strtolower($item) }}" class="text-gray-400 hover:text-primary transition italic text-sm font-light">{{ $item }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold tracking-widest uppercase text-sm mb-10 text-gray-500">Newsletter</h4>
                    <div class="flex gap-2 mb-6">
                        <input type="email" placeholder="Your Email" class="bg-white/5 border border-white/10 px-4 py-3 {{ $radiusClass }} w-full focus:ring-1 focus:ring-primary text-white">
                        <button class="bg-primary text-white px-6 {{ $radiusClass }} hover:opacity-90 transition inline-flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 leading-relaxed italic font-light">Join our community for exclusive updates.</p>
                </div>
            </div>

            <div class="border-t border-white/10 pt-10 flex flex-col md:flex-row justify-between items-center text-xs tracking-[0.3em] text-gray-500 uppercase">
                <p>&copy; {{ date('Y') }} {{ $website->content['business_name'] ?? tenant('name') }}. All Rights Reserved.</p>
                <div class="flex gap-8 mt-6 md:mt-0">
                    <a href="#" class="hover:text-primary transition">Terms & Conditions</a>
                    <a href="#" class="hover:text-primary transition">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
        });
    </script>
</body>

</html>