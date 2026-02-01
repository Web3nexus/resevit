@php
    $settings = $website->content['settings'] ?? [];
    $primaryColor = $settings['primary_color'] ?? '#FF2D55';
    $borderRadius = $settings['border_radius'] ?? 'full';
    $fontFamily = $settings['font_family'] ?? 'Quicksand';

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

    $radiusClass = $radiusMap[$borderRadius] ?? 'rounded-full';

    $googleFonts = [
        'Inter' => 'Inter:wght@400;700',
        'Outfit' => 'Outfit:wght@400;600;700',
        'Playfair Display' => 'Playfair+Display:wght@700;900',
        'Montserrat' => 'Montserrat:wght@400;700;900',
        'Quicksand' => 'Quicksand:wght@400;700',
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
    <link
        href="https://fonts.googleapis.com/css2?family={{ $fontUrl }}&family=Playfair+Display:wght@700;900&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['{{ $fontFamily }}', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        primary: '{{ $primaryColor }}',
                        accent: '#FFB800',
                        soft: '#FFF0F3',
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

<body class="font-sans text-gray-800 antialiased bg-white" x-data="{ scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navigation -->
    <nav :class="scrolled ? 'bg-white shadow-sm py-2' : 'bg-transparent py-4'"
        class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <span
                    class="text-2xl font-black text-gray-900 tracking-tight">{{ $website->content['business_name'] ?? tenant('name') }}</span>
            </a>

            <div class="hidden lg:flex items-center space-x-10 text-sm font-bold text-gray-600">
                <a href="#home" class="hover:text-primary transition">Home</a>
                <a href="/menu" class="hover:text-primary transition">Menu</a>
                <a href="/reservations" class="hover:text-primary transition">Booking</a>
                <a href="#featured" class="hover:text-primary transition">Featured</a>
                <a href="#news" class="hover:text-primary transition">News</a>
            </div>

            <div class="flex items-center gap-6">
                <a href="/reservations"
                    class="bg-primary text-white px-8 py-2.5 {{ $radiusClass }} font-bold text-xs hover:shadow-lg transition uppercase">Get
                    Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-[90vh] bg-soft flex items-center overflow-hidden">
        <div class="container mx-auto px-6 grid lg:grid-cols-2 gap-20 items-center pt-20">
            <div data-aos="fade-right">
                <span
                    class="text-primary font-bold uppercase tracking-widest text-sm mb-4 block">{{ $website->content['hero_badge'] ?? 'Welcome to Resevit' }}</span>
                <h1 class="text-gray-900 text-6xl lg:text-7xl font-serif font-black mb-10 leading-tight">
                    {{ $website->content['hero_title'] ?? 'Enjoy Our Delicious Meal' }}
                </h1>
                <div class="flex items-center gap-6">
                    <button
                        class="bg-primary text-white px-10 py-4 rounded-full font-bold hover:shadow-xl transition shadow-lg shadow-primary/20">Explore
                        Now</button>
                    <button class="flex items-center gap-3 group text-gray-600 font-bold">
                        <span
                            class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center group-hover:bg-white group-hover:border-primary transition">▶</span>
                        Watch Video
                    </button>
                </div>
            </div>

            <div class="relative" data-aos="zoom-in">
                <div class="absolute inset-0 bg-white/50 rounded-full blur-3xl"></div>
                <!-- Large dish image -->
                <div
                    class="relative w-full aspect-square max-w-lg mx-auto bg-white rounded-full p-4 shadow-2xl overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?q=80&w=2070"
                        class="w-full h-full object-cover">
                </div>
                <!-- Mini elements -->
                <div
                    class="absolute top-10 right-0 w-24 h-24 bg-accent rounded-full flex items-center justify-center text-white font-bold text-xl rotate-12 shadow-xl">
                    100% FRESH</div>
            </div>
        </div>
    </section>

    <!-- Menu Strip -->
    <section class="py-8 bg-primary">
        <div class="container mx-auto px-6 overflow-x-auto">
            <div class="flex justify-between items-center gap-8 min-w-max">
                <span class="text-white font-serif font-black text-2xl rotate-12 -ml-4">Top<br>Menu</span>
                @foreach(['Steak', 'Sandwich', 'Pasta', 'Pizza', 'Salads', 'Seafood', 'Chicken'] as $cat)
                    <div class="flex flex-col items-center gap-2 group cursor-pointer">
                        <div
                            class="w-20 h-20 bg-white rounded-full p-3 group-hover:scale-110 transition shadow-lg overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=2070"
                                class="w-full h-full object-cover">
                        </div>
                        <span class="text-white font-bold text-xs uppercase tracking-widest">{{ $cat }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Delivery Action -->
    <section class="py-24 bg-white overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-3 gap-12 items-center">
                <div class="text-center lg:text-left" data-aos="fade-right">
                    <h3 class="text-primary font-serif font-black text-3xl mb-4">Fast & Reliable<br>30 Minutes Delivery!
                    </h3>
                    <p class="text-gray-400 text-sm italic">Fresh and hot food delivered to your doorstep in no time.
                    </p>
                </div>
                <div class="flex justify-center" data-aos="zoom-in">
                    <span class="text-[150px] leading-none opacity-20">🛵</span>
                </div>
                <div class="text-center lg:text-right" data-aos="fade-left">
                    <h4 class="text-gray-900 font-bold mb-4">Choose what you want,<br>when you want it.</h4>
                    <a href="/menu"
                        class="inline-block bg-primary text-white px-8 py-3 {{ $radiusClass }} font-bold text-xs shadow-lg shadow-primary/20 hover:scale-105 transition">Order
                        Now</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Promo Highlights Grid -->
    <section id="featured" class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 h-[500px]">
                @php
                    $h7Featured = \App\Models\MenuItem::where('is_active', true)->where('is_available', true)->orderBy('sort_order')->take(4)->get();
                @endphp
                @foreach($h7Featured as $idx => $featured)
                    <div class="relative rounded-2xl overflow-hidden group cursor-pointer {{ $idx === 0 ? 'lg:row-span-2' : '' }} {{ $idx === 1 ? 'lg:col-span-2' : '' }}"
                        data-aos="fade-up" @click="$dispatch('add-to-cart', { menuItemId: {{ $featured->id }} })">
                        <img src="{{ \App\Helpers\StorageHelper::getUrl($featured->image_path) ?? 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd' }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-8">
                            <h4 class="text-white font-serif font-black text-3xl mb-4 italic">{{ $featured->name }}</h4>
                            <button
                                class="bg-primary text-white px-6 py-2 rounded-full font-bold text-xs w-fit uppercase">Add
                                to Cart</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section id="testimonial" class="py-32 bg-white overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="relative" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1522336572018-3488737cc530?q=80&w=1964"
                        class="w-full rounded-[4rem] shadow-2xl -rotate-2">
                    <div
                        class="absolute -bottom-10 -right-10 bg-white p-8 rounded-2xl shadow-xl flex items-center gap-4">
                        <img src="https://randomuser.me/api/portraits/thumb/men/1.jpg" class="w-12 h-12 rounded-full">
                        <div>
                            <span class="block font-bold">Thomas Smith</span>
                            <span class="text-xs text-gray-400">Regular Customer</span>
                        </div>
                    </div>
                </div>
                <div data-aos="fade-left">
                    <span class="text-primary font-bold uppercase tracking-widest text-sm mb-6 block">Testimonial</span>
                    <h2 class="text-4xl lg:text-5xl font-serif font-black text-gray-900 mb-10 leading-tight">Customer
                        Why About Us</h2>
                    <blockquote class="text-gray-500 text-xl leading-loose italic mb-10">
                        "Choosing food from here was the best decision for our family dinner. Every dish was cooked to
                        perfection and the flavors were absolutely authentic. We couldn't ask for more!"
                    </blockquote>
                    <div class="flex gap-4">
                        <button
                            class="w-12 h-12 rounded-full border border-gray-100 flex items-center justify-center hover:bg-primary hover:text-white transition">←</button>
                        <button
                            class="w-12 h-12 rounded-full border border-gray-100 flex items-center justify-center hover:bg-primary hover:text-white transition">→</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section id="news" class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-serif font-black text-gray-900 mb-4">Latest News & Blog</h2>
                <div class="w-20 h-2 bg-primary mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach(['New Menu Item', 'Cooking Masterclass', 'Health & Flavor', 'Local Ingredients'] as $news)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group"
                        data-aos="fade-up">
                        <div class="h-48 overflow-hidden relative">
                            <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?q=80&w=2084"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            <span
                                class="absolute top-4 left-4 bg-primary text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-tighter">Food</span>
                        </div>
                        <div class="p-6">
                            <span class="text-gray-400 text-xs font-bold mb-2 block italic uppercase tracking-widest">Oct
                                12, 2024</span>
                            <h4 class="text-gray-900 font-bold mb-4 leading-tight group-hover:text-primary transition">
                                {{ $news }}
                            </h4>
                            <a href="#" class="text-primary font-bold text-sm flex items-center gap-2 group/link">Read More
                                <span class="group-hover/link:translate-x-2 transition">→</span></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white pt-24 pb-12 overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-20 mb-20 text-center md:text-left">
                <div class="md:col-span-1">
                    <span
                        class="text-4xl font-black text-gray-900 mb-8 block">{{ $website->content['business_name'] ?? tenant('name') }}</span>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-xs mb-8 italic">
                        {{ $website->content['footer_about'] ?? 'Fresh, authentic, delivered with love since 2011.' }}
                    </p>
                </div>
                <div>
                    <h5 class="font-bold text-gray-900 mb-8 uppercase tracking-widest text-xs">Explore</h5>
                    <ul class="space-y-4 text-gray-400 text-sm font-bold italic">
                        <li><a href="#" class="hover:text-primary transition">Our Story</a></li>
                        <li><a href="#" class="hover:text-primary transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-primary transition">Terms of Use</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-gray-900 mb-8 uppercase tracking-widest text-xs">Categories</h5>
                    <ul class="space-y-4 text-gray-400 text-sm font-bold italic">
                        <li><a href="#" class="hover:text-primary transition">Beef & Chicken</a></li>
                        <li><a href="#" class="hover:text-primary transition">Vegan Options</a></li>
                        <li><a href="#" class="hover:text-primary transition">Drink Specials</a></li>
                    </ul>
                </div>
                <div class="bg-primary p-12 rounded-[2rem] text-white">
                    <h5 class="font-bold mb-6 uppercase tracking-widest text-xs italic">Newsletter</h5>
                    <input type="email" placeholder="Email Address..."
                        class="w-full bg-white/10 border-none px-6 py-4 rounded-full mb-4 text-sm focus:ring-1 focus:ring-white">
                    <button
                        class="w-full bg-dark text-white font-black py-4 rounded-full hover:bg-white hover:text-primary transition tracking-widest uppercase text-xs">Register</button>
                </div>
            </div>
            <div
                class="border-t border-gray-100 pt-12 text-center text-[10px] font-black text-gray-300 uppercase tracking-[0.5em]">
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