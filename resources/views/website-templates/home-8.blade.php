@php
    $settings = $website->content['settings'] ?? [];
    $primaryColor = $settings['primary_color'] ?? '#10B981';
    $borderRadius = $settings['border_radius'] ?? '2xl';
    $fontFamily = $settings['font_family'] ?? 'Inter';

    $radiusMap = [
        'none' => 'rounded-none', 'sm' => 'rounded-sm', 'md' => 'rounded-md', 'lg' => 'rounded-lg',
        'xl' => 'rounded-xl', '2xl' => 'rounded-2xl', '3xl' => 'rounded-3xl', 'full' => 'rounded-full',
    ];

    $radiusClass = $radiusMap[$borderRadius] ?? 'rounded-2xl';
    
    $googleFonts = [
        'Inter' => 'Inter:wght@400;700',
        'Outfit' => 'Outfit:wght@400;600;700',
        'Playfair Display' => 'Playfair+Display:wght@400;700',
        'Montserrat' => 'Montserrat:wght@400;700',
        'Space Grotesk' => 'Space+Grotesk:wght@400;700',
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
    <link href="https://fonts.googleapis.com/css2?family={{ $fontUrl }}&family=Fredoka+One&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['{{ $fontFamily }}', 'sans-serif'],
                        accent: ['Fredoka One', 'cursive'],
                    },
                    colors: {
                        primary: '{{ $primaryColor }}',
                        dark: '#111827',
                        pinky: '#FFEDF2',
                        accent: '#FBBF24',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: '{{ $fontFamily }}', sans-serif; }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased bg-white" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navigation -->
    <nav :class="scrolled ? 'bg-white shadow-sm py-2' : 'bg-transparent py-5'" class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <span class="text-2xl font-accent text-primary tracking-tight">{{ $website->content['business_name'] ?? tenant('name') }}</span>
            </a>

            <div class="hidden lg:flex items-center space-x-10 text-sm font-bold" :class="scrolled ? 'text-dark' : 'text-gray-600'">
                <a href="#home" class="hover:text-primary transition">Home</a>
                <a href="/menu" class="hover:text-primary transition">Menu</a>
                <a href="/reservations" class="hover:text-primary transition">Booking</a>
            </div>

            <div class="flex items-center gap-6">
                <a href="/menu" class="bg-primary text-white px-8 py-2.5 {{ $radiusClass }} font-bold text-xs hover:shadow-lg transition">Order Now</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-[70vh] flex items-center bg-white overflow-hidden pt-20">
        <div class="container mx-auto px-6 text-center">
            <div data-aos="zoom-in">
                <h1 class="text-6xl lg:text-[100px] font-accent text-dark leading-none mb-10 uppercase tracking-tighter">
                    {{ $website->content['hero_title'] ?? 'GRILLED BEEF BURGER' }}
                </h1>
                <a href="/menu" class="inline-block bg-primary text-white px-12 py-5 {{ $radiusClass }} font-black tracking-widest hover:bg-dark transition shadow-xl uppercase mb-20 text-xs">Shop Now</a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4" data-aos="fade-up">
                @foreach(['Beef Burger', 'Pizza', 'Pasta', 'Steak', 'Dessert'] as $item)
                <div class="bg-gray-50 p-6 rounded-2xl group hover:bg-white hover:shadow-xl transition flex flex-col items-center">
                    <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=200" class="w-16 h-16 rounded-full mb-4 group-hover:scale-110 transition">
                    <span class="font-bold text-xs uppercase tracking-widest">{{ $item }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Weekend Deals Carousel-style -->
    <section id="offers" class="py-24 bg-white overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-accent text-dark">Weekend Deals</h2>
            </div>
            <div class="flex flex-wrap justify-center gap-8">
                @foreach([
                    ['name' => 'Fresh Coconut', 'price' => '$12', 'color' => 'bg-pinky'],
                    ['name' => 'Strawberry Mix', 'price' => '$15', 'color' => 'bg-green-50'],
                    ['name' => 'Fresh Watermelon', 'price' => '$10', 'color' => 'bg-pinky']
                ] as $deal)
                <div class="{{ $deal['color'] }} p-12 rounded-[4rem] text-center min-w-[320px] flex flex-col items-center group cursor-pointer" data-aos="fade-up">
                    <img src="https://images.unsplash.com/photo-1543353071-873f17a7a088?q=80&w=300" class="w-40 h-40 rounded-full mb-8 shadow-2xl group-hover:scale-110 transition duration-500">
                    <h3 class="text-2xl font-accent text-dark mb-4">{{ $deal['name'] }}</h3>
                    <span class="text-primary font-black text-xl mb-6">{{ $deal['price'] }}</span>
                    <button class="bg-primary text-white w-12 h-12 rounded-full flex items-center justify-center hover:scale-110 transition">🛒</button>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Products with Filter -->
    <section id="products" class="py-24 bg-[#FFF0F5] border-y border-gray-100">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-accent text-dark mb-12">Farm Fresh Products</h2>
            
            <div class="flex justify-center gap-8 mb-16 font-bold text-xs uppercase tracking-widest text-gray-400">
                <button class="text-primary border-b-2 border-primary pb-2">ALL</button>
                <button class="hover:text-primary transition">BREAKFAST</button>
                <button class="hover:text-primary transition">LUNCH</button>
                <button class="hover:text-primary transition">DINNER</button>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $h8MenuItems = \App\Models\MenuItem::where('is_active', true)->orderBy('sort_order')->take(8)->get();
                @endphp
                @foreach($h8MenuItems as $item)
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition group" data-aos="fade-up">
                    <div class="aspect-square rounded-2xl overflow-hidden mb-6">
                        <img src="{{ \App\Helpers\StorageHelper::getUrl($item->image_path) ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=400' }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    </div>
                    <div class="text-left">
                        <h4 class="font-bold text-dark mb-2">{{ $item->name }}</h4>
                        <div class="flex justify-between items-center">
                            <span class="text-primary font-black">${{ number_format($item->base_price, 2) }}</span>
                            <button @click="$dispatch('add-to-cart', { menuItemId: {{ $item->id }} })" 
                                class="w-8 h-8 rounded-full border border-gray-100 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition">🛒</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section class="py-32 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="relative" data-aos="fade-right">
                    <div class="w-full aspect-square bg-accent rounded-full opacity-10 absolute scale-125 -z-10"></div>
                    <img src="https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2" class="w-full rounded-[4rem] shadow-2xl">
                </div>
                <div data-aos="fade-left">
                    <h2 class="text-5xl font-accent mb-10 leading-tight">Why We Are<br>What We Are?</h2>
                    <ul class="space-y-8">
                        @foreach(['Fast Home Delivery', '100% Fresh Ingredients', 'Professional Chefs'] as $f)
                        <li class="flex items-center gap-6">
                            <span class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xl">✓</span>
                            <span class="font-bold text-lg">{{ $f }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial -->
    <section class="py-24 bg-primary text-white text-center">
        <div class="container mx-auto px-6 max-w-4xl">
            <h2 class="text-4xl font-accent mb-12">Testimonial</h2>
            <blockquote class="text-2xl font-bold italic mb-12 leading-relaxed opacity-90">
                "Finding fresh and healthy food delivered so fast was a game changer for me. I highly recommend Foodly to all my friends and family!"
            </blockquote>
            <div class="flex justify-center gap-4">
                @foreach([1,2,3,4,5] as $i)
                    <img src="https://randomuser.me/api/portraits/thumb/men/{{$i}}.jpg" class="w-12 h-12 rounded-full border-2 border-white/20 hover:scale-110 transition cursor-pointer">
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white pt-24 pb-12 border-t border-gray-50">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-20 mb-16 text-center md:text-left">
                <div class="md:col-span-1">
                    <span class="text-4xl font-accent text-primary mb-8 block tracking-tighter">Foodly</span>
                    <p class="text-gray-400 text-sm font-bold leading-relaxed max-w-xs">Connecting farm-fresh quality with urban speed since 2011.</p>
                </div>
                <div>
                    <h5 class="font-bold text-dark mb-8 uppercase tracking-widest text-xs">Navigation</h5>
                    <ul class="space-y-4 text-gray-400 text-sm font-medium">
                        <li><a href="#" class="hover:text-primary transition">About Us</a></li>
                        <li><a href="#" class="hover:text-primary transition">Menu Items</a></li>
                        <li><a href="#" class="hover:text-primary transition">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-dark mb-8 uppercase tracking-widest text-xs">Contact</h5>
                    <ul class="space-y-4 text-gray-400 text-sm font-medium italic">
                        <li>hello@foodly.com</li>
                        <li>+1 (555) 123-4567</li>
                        <li>88 Great Ave, NY</li>
                    </ul>
                </div>
                <div class="bg-gray-900 p-10 rounded-[3rem] text-white">
                    <h5 class="font-accent text-xl mb-6 text-primary tracking-widest uppercase">Newsletter</h5>
                    <input type="email" placeholder="Email..." class="w-full bg-white/10 border-none px-6 py-3 rounded-xl mb-4 text-sm focus:ring-1 focus:ring-primary">
                    <button class="w-full bg-primary text-white font-black py-3 rounded-xl hover:scale-105 transition tracking-widest uppercase text-xs">Send</button>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-12 text-center text-[10px] font-black text-gray-300 uppercase tracking-[1em]">
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