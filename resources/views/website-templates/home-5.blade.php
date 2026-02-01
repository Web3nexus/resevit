@php
    $settings = $website->content['settings'] ?? [];
    $primaryColor = $settings['primary_color'] ?? '#FF003D';
    $borderRadius = $settings['border_radius'] ?? 'none';
    $fontFamily = $settings['font_family'] ?? 'Inter';

    $radiusMap = [
        'none' => 'rounded-none', 'sm' => 'rounded-sm', 'md' => 'rounded-md', 'lg' => 'rounded-lg',
        'xl' => 'rounded-xl', '2xl' => 'rounded-2xl', '3xl' => 'rounded-3xl', 'full' => 'rounded-full',
    ];

    $radiusClass = $radiusMap[$borderRadius] ?? 'rounded-none';
    
    $googleFonts = [
        'Inter' => 'Inter:wght@400;700',
        'Outfit' => 'Outfit:wght@400;600;700',
        'Playfair Display' => 'Playfair+Display:wght@400;700',
        'Montserrat' => 'Montserrat:wght@400;700;900',
        'Pathway Extreme' => 'Pathway+Extreme:wght@400;700;900',
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
    <link href="https://fonts.googleapis.com/css2?family={{ $fontUrl }}&family=Pathway+Extreme:wght@400;700;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['{{ $fontFamily }}', 'sans-serif'],
                        heading: ['"Pathway Extreme"', 'sans-serif'],
                    },
                    colors: {
                        primary: '{{ $primaryColor }}',
                        secondary: '#FF8A00',
                        dark: '#1A1A1A',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: '{{ $fontFamily }}', sans-serif; }
    </style>
</head>

<body class="font-sans text-dark antialiased bg-white" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navigation -->
    <nav :class="scrolled ? 'bg-white shadow-sm py-2' : 'bg-transparent py-4'" class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <span class="text-2xl font-heading font-black tracking-tighter text-dark italic">{{ strtoupper($website->content['business_name'] ?? tenant('name')) }}</span>
            </a>

            <div class="hidden lg:flex items-center space-x-10 text-xs font-bold uppercase tracking-widest text-dark/80">
                <a href="#home" class="hover:text-primary transition">Home</a>
                <a href="/menu" class="hover:text-primary transition">Menu</a>
                <a href="/reservations" class="hover:text-primary transition">Booking</a>
                <a href="#contact" class="hover:text-primary transition">Contact</a>
            </div>

            <div class="flex items-center gap-6">
                <a href="/reservations" class="bg-primary text-white px-6 py-2.5 {{ $radiusClass }} font-black text-xs hover:bg-dark transition shadow-lg shadow-primary/20 uppercase">BOOK NOW</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-[90vh] bg-dark flex items-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2070" class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/40 to-transparent"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-3xl" data-aos="fade-right">
                <h1 class="text-white text-6xl lg:text-8xl font-heading font-black mb-10 leading-none uppercase">
                    {{ $website->content['hero_title'] ?? 'The best Food Collection 2024' }}
                </h1>
                
                <div class="flex flex-col md:flex-row gap-4 max-w-xl">
                    <div class="flex-1 bg-white/10 backdrop-blur-md rounded-sm p-2 flex items-center gap-4 border border-white/10">
                        <input type="text" placeholder="Search your food..." class="bg-transparent border-none text-white placeholder-white/40 px-4 w-full focus:ring-0">
                        <button class="bg-primary text-white px-6 py-3 rounded-sm font-black text-xs">FIND</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Float Accent -->
        <div class="absolute right-10 bottom-10 w-32 h-32 bg-primary rounded-full flex items-center justify-center font-heading font-black text-white text-2xl rotate-12 shadow-2xl">
            20% OFF
        </div>
    </section>

    <!-- Quick Category Action -->
    <section class="py-12 bg-white -mt-10 relative z-20">
        <div class="container mx-auto px-6 grid md:grid-cols-3 gap-6">
            @foreach([
                ['title' => 'Delicious & Hot Pizza', 'color' => 'bg-dark', 'img' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=2070'],
                ['title' => 'French Fry Special', 'color' => 'bg-secondary', 'img' => 'https://images.unsplash.com/photo-1573082852240-5b65104d4444?q=80&w=1780'],
                ['title' => 'Chiken & Fried Fry', 'color' => 'bg-primary', 'img' => 'https://images.unsplash.com/photo-1562967914-608f82629710?q=80&w=2073']
            ] as $promo)
            <div class="{{ $promo['color'] }} rounded-sm p-8 flex items-center justify-between group overflow-hidden relative min-h-[160px]" data-aos="zoom-in">
                <div class="relative z-10">
                    <h4 class="text-white font-heading font-black text-xl mb-4 leading-tight">{{ $promo['title'] }}</h4>
                    <button class="text-white border-b border-white text-[10px] font-black tracking-widest uppercase pb-1 group-hover:text-white transition">Order Now</button>
                </div>
                <img src="{{ $promo['img'] }}" class="w-32 absolute -right-4 -bottom-4 transform group-hover:scale-110 transition duration-500">
            </div>
            @endforeach
        </div>
    </section>

    <!-- Feature Section -->
    <section id="about" class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="relative" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1574071318508-1cdbad80ad50?q=80&w=2070" class="w-full h-[500px] object-cover rounded-sm shadow-2xl">
                    <div class="absolute -bottom-10 -right-10 bg-accent p-8 rounded-sm shadow-xl hidden md:block">
                        <span class="font-heading font-black text-4xl block leading-none">12+</span>
                        <span class="text-xs font-bold uppercase tracking-widest opacity-60">Years Experience</span>
                    </div>
                </div>
                <div data-aos="fade-left">
                    <h2 class="text-4xl lg:text-6xl font-heading font-black text-dark mb-10 leading-tight">The Best Delicious Food<br>Made From Us...</h2>
                    <p class="text-gray-500 mb-12">We provide a unique dining experience where traditional flavors meet modern innovation. Our chefs carefully select the best ingredients.</p>
                    <div class="grid grid-cols-3 gap-8 mb-12">
                        @foreach(['250+ Recipes', '15+ Chefs', '100% Organic'] as $stat)
                        <div class="text-center">
                            <span class="text-primary font-heading font-black text-3xl block mb-2">{{ explode(' ', $stat)[0] }}</span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ implode(' ', array_slice(explode(' ', $stat), 1)) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <button class="bg-dark text-white px-10 py-4 rounded-sm font-black tracking-widest hover:bg-primary transition uppercase text-xs">Learn More</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="py-24 bg-gray-50 border-y border-gray-100">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl lg:text-5xl font-heading font-black mb-16">Hot Delicious Item</h2>
            
            <div class="flex justify-center gap-4 mb-16">
                @foreach(['Breakfast', 'Lunch', 'Dinner', 'Drinks'] as $tab)
                    <button class="{{ $loop->first ? 'bg-primary text-white' : 'bg-white text-dark hover:bg-gray-100' }} px-8 py-3 rounded-sm font-black text-xs uppercase tracking-widest transition shadow-sm">{{ $tab }}</button>
                @endforeach
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $h5MenuItems = \App\Models\MenuItem::where('is_active', true)->orderBy('sort_order')->take(4)->get();
                @endphp
                @foreach($h5MenuItems as $item)
                <div class="bg-white p-6 rounded-sm shadow-sm hover:shadow-xl transition group" data-aos="fade-up">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full overflow-hidden shadow-inner group-hover:scale-110 transition duration-500">
                        <img src="{{ \App\Helpers\StorageHelper::getUrl($item->image_path) ?? 'https://images.unsplash.com/photo-1541745537411-b8046dc6d66c?q=80&w=2188' }}" class="w-full h-full object-cover">
                    </div>
                    <h4 class="font-bold text-lg mb-2">{{ $item->name }}</h4>
                    <span class="text-primary font-black text-xl block mb-6 px-2">${{ number_format($item->base_price, 2) }}</span>
                    <button @click="$dispatch('add-to-cart', { menuItemId: {{ $item->id }} })" 
                            class="text-[10px] text-gray-400 font-bold mb-6 italic uppercase tracking-widest hover:text-primary transition bg-transparent border-none p-0 cursor-pointer">Order Online</button>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Delivery Banner -->
    <section class="py-24 bg-primary relative overflow-hidden">
        <div class="container mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div class="text-white" data-aos="fade-right">
                <h2 class="text-5xl lg:text-7xl font-heading font-black leading-none mb-10 uppercase italic">30 Minutes Fast<br><span class="text-dark underline decoration-accent">Delivery</span> Challenge</h2>
                <button class="bg-white text-primary px-10 py-4 rounded-sm font-black tracking-widest hover:bg-dark hover:text-white transition uppercase text-xs">Claim Challenge</button>
            </div>
            <div data-aos="fade-left" class="flex justify-center">
                <div class="w-64 h-64 bg-white/20 rounded-full flex items-center justify-center animate-pulse">
                    <span class="text-8xl">🛵</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Special Grid -->
    <section class="py-24 bg-dark">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-0">
            @foreach([
                ['title' => 'Today Special Delicious', 'img' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591', 'color' => 'bg-dark'],
                ['title' => 'Recipe Combo Deal', 'img' => 'https://images.unsplash.com/photo-1550547660-d9450f859349', 'color' => 'bg-secondary'],
                ['title' => 'Fresh Food Meal', 'img' => 'https://images.unsplash.com/photo-1574071318508-1cdbad80ad50', 'color' => 'bg-secondary'],
                ['title' => 'Tapper Breakfast', 'img' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8', 'color' => 'bg-dark']
            ] as $cell)
            <div class="relative h-[300px] group overflow-hidden cursor-pointer" data-aos="zoom-in">
                <img src="{{ $cell['img'] }}?q=80&w=1000" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-60">
                <div class="absolute inset-0 bg-black/40 group-hover:bg-primary/40 transition"></div>
                <div class="absolute inset-0 p-8 flex flex-col justify-end">
                    <h4 class="text-white font-heading font-black text-2xl mb-4 leading-tight uppercase">{{ $cell['title'] }}</h4>
                    <button class="bg-accent text-dark px-4 py-2 rounded-sm font-black text-[10px] w-fit uppercase">Order Now</button>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white pt-24 pb-12 overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-20 mb-20 text-center md:text-left">
                <div class="md:col-span-1">
                    <span class="text-4xl font-heading font-black text-dark mb-8 block italic">FEASTY</span>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-xs mb-8">Delivering happiness through high-quality delicious food collection since 2011.</p>
                </div>
                <div>
                    <h5 class="font-black text-dark mb-8 uppercase tracking-widest text-xs">Categories</h5>
                    <ul class="space-y-4 text-gray-400 text-sm font-bold uppercase tracking-tighter">
                        <li><a href="#" class="hover:text-primary transition">Pizza & Fast Food</a></li>
                        <li><a href="#" class="hover:text-primary transition">Healthy Salads</a></li>
                        <li><a href="#" class="hover:text-primary transition">Drinks & Dessert</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-black text-dark mb-8 uppercase tracking-widest text-xs">Contact</h5>
                    <ul class="space-y-4 text-gray-400 text-sm font-bold italic">
                        <li>info@feasty.com</li>
                        <li>+1 (555) 000-000</li>
                        <li>123 Food Street, NY</li>
                    </ul>
                </div>
                <div class="bg-primary p-12 rounded-sm text-white">
                    <h5 class="font-black mb-6 uppercase tracking-widest text-xs italic">Newsletter</h5>
                    <input type="email" placeholder="Email..." class="w-full bg-white/10 border-none px-6 py-4 rounded-sm mb-4 text-sm focus:ring-1 focus:ring-white">
                    <button class="w-full bg-dark text-white font-black py-4 rounded-sm hover:bg-white hover:text-primary transition tracking-widest uppercase text-xs">Send</button>
                </div>
            </div>
            <div class="border-t border-gray-100 pt-12 text-center text-[10px] font-black text-gray-300 uppercase tracking-[0.5em]">
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