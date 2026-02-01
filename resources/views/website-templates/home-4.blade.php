@php
    $settings = $website->content['settings'] ?? [];
    $primaryColor = $settings['primary_color'] ?? '#E31837';
    $borderRadius = $settings['border_radius'] ?? 'lg';
    $fontFamily = $settings['font_family'] ?? 'Montserrat';

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
        'Inter' => 'Inter:wght@400;700',
        'Outfit' => 'Outfit:wght@400;600;700',
        'Playfair Display' => 'Playfair+Display:wght@400;700',
        'Montserrat' => 'Montserrat:wght@400;700;900',
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
    <link href="https://fonts.googleapis.com/css2?family={{ $fontUrl }}&family=Teko:wght@400;600;700&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['{{ $fontFamily }}', 'sans-serif'],
                        teko: ['Teko', 'sans-serif'],
                    },
                    colors: {
                        primary: '{{ $primaryColor }}',
                        accent: '#FFC72C',
                        dark: '#121212',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: '{{ $fontFamily }}', sans-serif;
        }
    </style>
</head>

<body class="font-sans text-dark antialiased bg-white" x-data="{ scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navigation -->
    <nav :class="scrolled ? 'bg-white shadow-lg py-2' : 'bg-transparent py-4'"
        class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <span
                    class="text-3xl font-teko font-bold text-primary tracking-tighter">{{ strtoupper($website->content['business_name'] ?? tenant('name')) }}</span>
            </a>

            <div class="hidden lg:flex items-center space-x-8 text-sm font-bold uppercase tracking-tighter"
                :class="scrolled ? 'text-dark' : 'text-white'">
                <a href="#home" class="hover:text-primary transition">Home</a>
                <a href="/menu" class="hover:text-primary transition">Menu</a>
                <a href="/reservations" class="hover:text-primary transition">Booking</a>
                <a href="#contact" class="hover:text-primary transition">Contact</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="/menu"
                    class="bg-accent text-dark px-6 py-2 {{ $radiusClass }} font-black text-xs hover:scale-105 transition shadow-lg shadow-accent/20 uppercase">ORDER
                    NOW</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen bg-[#600000] flex items-center overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 text-8xl">🍔</div>
            <div class="absolute bottom-20 right-20 text-8xl">🍕</div>
            <div class="absolute top-1/2 left-1/4 text-6xl">🍟</div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <span
                        class="text-accent font-teko text-3xl mb-4 block tracking-widest">{{ $website->content['hero_badge'] ?? 'Fresh & Tasty' }}</span>
                    <h1 class="text-white text-7xl lg:text-9xl font-black leading-[0.9] mb-8 uppercase italic">
                        {{ $website->content['hero_title'] ?? 'HOT SPICY CHIKEN BURGER' }}
                    </h1>
                    <p class="text-white/70 text-lg mb-10 max-w-md font-bold">
                        {{ $website->content['hero_subtitle'] ?? 'Experience the best flavors in town with our signature dishes.' }}
                    </p>
                    <a href="/menu"
                        class="inline-block bg-primary text-white px-10 py-5 {{ $radiusClass }} font-black tracking-[0.2em] hover:bg-white hover:text-primary transition uppercase shadow-2xl">Order
                        Now</a>
                </div>

                <div class="relative" data-aos="zoom-in">
                    <div class="absolute inset-0 bg-primary rounded-full blur-[150px] opacity-30"></div>
                    <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=1899"
                        class="w-full relative z-10 drop-shadow-[0_45px_45px_rgba(0,0,0,0.6)] transform hover:scale-105 transition duration-700">
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Items -->
    <section id="popular" class="py-24 bg-white relative overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-primary font-bold uppercase tracking-widest text-sm mb-2 block">Our Specialties</span>
                <h2 class="text-4xl lg:text-6xl font-black uppercase italic">Popular Food Items</h2>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach(['Chiken', 'Hot Burger', 'Thin Paste', 'Hot Pizza'] as $item)
                    <div class="text-center group" data-aos="fade-up">
                        <div
                            class="w-40 h-40 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-6 group-hover:bg-primary group-hover:scale-110 transition duration-500 shadow-xl overflow-hidden relative">
                            <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=2070"
                                class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-black text-xl mb-2 uppercase">{{ $item }}</h4>
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-widest italic">View More</span>
                    </div>
                @endforeach
            </div>

            <!-- Promo Banners -->
            <div class="grid lg:grid-cols-2 gap-8 mt-24">
                <div class="relative bg-dark rounded-3xl p-12 overflow-hidden h-64 flex items-center group cursor-pointer"
                    data-aos="fade-right">
                    <div class="relative z-10">
                        <span class="text-accent font-black uppercase tracking-widest text-sm mb-4 block">Limited
                            Time</span>
                        <h3 class="text-white text-4xl font-black uppercase italic leading-none">SUPER
                            DELICIOUS<br>BURGER COMBO</h3>
                    </div>
                    <img src="https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=1965"
                        class="absolute -right-10 top-1/2 -translate-y-1/2 w-64 group-hover:scale-110 transition duration-700">
                </div>
                <div class="relative bg-primary rounded-3xl p-12 overflow-hidden h-64 flex items-center group cursor-pointer"
                    data-aos="fade-left">
                    <div class="relative z-10">
                        <span class="text-white font-black uppercase tracking-widest text-sm mb-4 block">Today
                            Deal</span>
                        <h3 class="text-white text-4xl font-black uppercase italic leading-none">BEST THIN
                            CRUST<br>ITALIAN PIZZA</h3>
                    </div>
                    <img src="https://images.unsplash.com/photo-1571407970349-bc81e7e96d47?q=80&w=1925"
                        class="absolute -right-10 top-1/2 -translate-y-1/2 w-64 group-hover:scale-110 transition duration-700">
                </div>
            </div>
        </div>
    </section>

    <!-- Daily Discount Strip -->
    <section class="py-24 bg-dark relative overflow-hidden">
        <div
            class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/black-felt.png')] opacity-30">
        </div>
        <div class="container mx-auto px-6 relative z-10 flex flex-col items-center text-center">
            <span class="text-accent font-teko text-4xl mb-4 tracking-widest uppercase" data-aos="fade-up">Fast &
                Affordable</span>
            <h2 class="text-white text-6xl lg:text-7xl font-black uppercase italic mb-8" data-aos="fade-up">Today's
                <span class="text-primary italic">Attackin</span> Day
            </h2>
            <p class="text-white/50 text-xl font-bold mb-10 max-w-2xl" data-aos="fade-up">Get 50% discount on all burger
                sets between 2PM and 5PM every Tuesday!</p>
            <button
                class="bg-primary text-white px-12 py-4 rounded-sm font-black tracking-widest hover:bg-white hover:text-primary transition uppercase shadow-xl"
                data-aos="zoom-in">CLAIM NOW</button>
        </div>
    </section>

    <!-- Menu Grid -->
    <section id="menu" class="py-24 bg-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl lg:text-6xl font-black uppercase italic mb-16">Popular Fast Foods</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
                @php
                    $h4MenuItems = \App\Models\MenuItem::where('is_active', true)->orderBy('sort_order')->take(8)->get();
                @endphp
                @foreach($h4MenuItems as $item)
                    <div class="text-left group cursor-pointer" data-aos="fade-up"
                        @click="$dispatch('add-to-cart', { menuItemId: {{ $item->id }} })">
                        <div class="aspect-square bg-gray-100 rounded-3xl overflow-hidden mb-6 shadow-md relative">
                            <img src="{{ \App\Helpers\StorageHelper::getUrl($item->image_path) ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1780' }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            <div
                                class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <span
                                    class="bg-white text-primary px-6 py-2 rounded-full font-black text-xs uppercase tracking-widest shadow-xl">Add
                                    to Cart</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center px-2">
                            <h4 class="font-black text-xl uppercase tracking-tighter group-hover:text-primary transition">
                                {{ $item->name }}</h4>
                            <span class="text-primary font-black text-xl">${{ number_format($item->base_price, 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="/menu"
                class="inline-block mt-20 border-2 border-primary text-primary px-10 py-4 {{ $radiusClass }} font-black tracking-widest hover:bg-primary hover:text-white transition uppercase">View
                All Menu</a>
        </div>
    </section>

    <!-- Delivery Banner -->
    <section class="py-24 bg-primary relative overflow-hidden">
        <!-- Scooter image would go here -->
        <div class="container mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div class="text-white" data-aos="fade-right">
                <h2 class="text-5xl lg:text-7xl font-black uppercase italic leading-none mb-8">30 Minutes Fast<br><span
                        class="text-accent underline">Delivery</span> Challenge</h2>
                <p class="text-white/80 text-lg font-bold mb-10">We promise to deliver your food within 30 minutes! If
                    we're late, your next meal is on us.</p>
                <button
                    class="bg-white text-primary px-10 py-4 rounded-sm font-black tracking-widest hover:bg-dark hover:text-white transition uppercase shadow-2xl tracking-[0.2em]">Learn
                    More</button>
            </div>
            <div class="relative" data-aos="fade-left">
                <!-- Scooter asset is visually represented here -->
                <div class="w-full h-[400px] flex items-center justify-center">
                    <span class="text-[200px] opacity-10 font-black italic">FASTY</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 pt-24 pb-12">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-20 mb-16 text-center md:text-left">
                <div class="col-span-1 md:col-span-1">
                    <span class="text-4xl font-teko font-bold text-primary mb-8 block tracking-tighter">FASTY</span>
                    <p class="text-gray-400 text-sm font-bold leading-loose">The fastest and tastiest food delivery in
                        your city. Fresh ingredients, master chefs, and 30-min delivery guarantee.</p>
                </div>
                <div>
                    <h5 class="font-black text-dark mb-8 uppercase tracking-widest text-xs italic">Quick Links</h5>
                    <ul class="space-y-4 text-gray-400 text-sm font-bold uppercase tracking-tighter">
                        <li><a href="#" class="hover:text-primary transition">Home</a></li>
                        <li><a href="#" class="hover:text-primary transition">Popular</a></li>
                        <li><a href="#" class="hover:text-primary transition">Menu</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-black text-dark mb-8 uppercase tracking-widest text-xs italic">Support</h5>
                    <ul class="space-y-4 text-gray-400 text-sm font-bold uppercase tracking-tighter">
                        <li><a href="#" class="hover:text-primary transition">Terms</a></li>
                        <li><a href="#" class="hover:text-primary transition">Privacy</a></li>
                        <li><a href="#" class="hover:text-primary transition">Refunds</a></li>
                    </ul>
                </div>
                <div class="bg-dark p-10 rounded-3xl text-white">
                    <h5 class="font-black mb-6 uppercase tracking-widest text-xs italic text-accent">Newsletter</h5>
                    <input type="email" placeholder="Email..."
                        class="w-full bg-white/10 border-none px-6 py-4 rounded-xl mb-4 text-sm focus:ring-2 focus:ring-primary">
                    <button
                        class="w-full bg-primary text-white font-black py-4 rounded-xl hover:scale-105 transition tracking-widest uppercase">Subscribe</button>
                </div>
            </div>
            <div
                class="border-t border-gray-100 pt-12 text-center text-[10px] font-black text-gray-300 uppercase tracking-[1em]">
                &copy; {{ date('Y') }} {{ $content['business_name'] ?? 'Resevit' }}
            </div>
        </div>
    </footer>

    <script>
        AOS.init({
            duration: 1000,
            once: true,
        });
    </script>
</body>

</html>