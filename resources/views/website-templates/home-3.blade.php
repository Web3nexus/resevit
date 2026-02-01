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
    $containerRadiusClass = $radiusMap[$borderRadius === 'none' ? 'none' : '3xl'] ?? 'rounded-3xl';

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
    <link href="https://fonts.googleapis.com/css2?family={{ $fontUrl }}&family=Bungee&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['{{ $fontFamily }}', 'sans-serif'],
                        accent: ['Bungee', 'cursive'],
                    },
                    colors: {
                        primary: '{{ $primaryColor }}',
                        accent: '#FFC837',
                        dark: '#0A0A0A',
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

<body class="font-sans text-dark antialiased bg-[#FAF9F6]" x-data="{ scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navigation -->
    <nav :class="scrolled ? 'bg-white shadow-lg py-2' : 'bg-transparent py-4'"
        class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <span class="text-3xl font-accent tracking-tighter"
                    :class="scrolled ? 'text-primary' : 'text-white'">{{ strtoupper($website->content['business_name'] ?? tenant('name')) }}</span>
            </a>

            <div class="hidden lg:flex items-center space-x-8 text-sm font-bold uppercase tracking-widest"
                :class="scrolled ? 'text-dark' : 'text-white'">
                <a href="#home" class="hover:text-primary transition">Home</a>
                <a href="#menu" class="hover:text-primary transition">Menu</a>
                <a href="#services" class="hover:text-primary transition">Services</a>
                <a href="/reservations" class="hover:text-primary transition">Booking</a>
            </div>

            <div class="flex items-center gap-6">
                <a href="/menu"
                    class="bg-accent text-dark px-6 py-2 {{ $radiusClass }} font-bold text-xs hover:scale-105 transition shadow-lg shadow-accent/20 uppercase">ORDER
                    ONLINE</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen bg-dark flex items-center overflow-hidden">
        <!-- Red background shape -->
        <div class="absolute right-0 top-0 w-1/2 h-full bg-primary -mr-32 skew-x-12"></div>

        <div class="container mx-auto px-6 relative z-10 pt-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <span class="text-accent font-accent text-xl mb-4 block tracking-widest">Premium Quality</span>
                    <h1 class="text-white text-7xl lg:text-9xl font-accent leading-none mb-8">
                        {{ $website->content['hero_title'] ?? strtoupper(tenant('name')) }}
                    </h1>
                    <p class="text-white/70 text-lg mb-10 max-w-md">
                        {{ $website->content['hero_subtitle'] ?? 'Experience the ultimate culinary journey with our handcrafted masterpieces.' }}
                    </p>
                    <div class="flex gap-4">
                        <a href="/menu"
                            class="bg-primary text-white px-8 py-4 {{ $radiusClass }} font-black tracking-widest hover:bg-white hover:text-primary transition uppercase text-center">ORDER
                            NOW</a>
                        <a href="/menu"
                            class="border border-white/30 text-white px-8 py-4 {{ $radiusClass }} font-black tracking-widest hover:bg-white hover:text-dark transition uppercase text-center">VIEW
                            MENU</a>
                    </div>
                </div>

                <div class="relative" data-aos="zoom-in">
                    <div class="absolute inset-0 bg-accent rounded-full blur-[120px] opacity-20"></div>
                    <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=1899"
                        class="w-full relative z-10 drop-shadow-[0_35px_35px_rgba(0,0,0,0.5)] transform hover:rotate-6 transition duration-1000">
                    <div
                        class="absolute top-10 right-10 w-24 h-24 bg-accent rounded-full flex flex-col items-center justify-center font-accent text-dark leading-none">
                        <span class="text-xs">ONLY</span>
                        <span class="text-2xl">$12</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Experience Section -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="flex gap-8 items-center" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1586816001966-79b736744398?q=80&w=2070"
                        class="w-1/2 h-[500px] object-cover {{ $containerRadiusClass }}">
                    <img src="https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=1965"
                        class="w-1/2 h-[400px] object-cover {{ $containerRadiusClass }} -mt-20">
                </div>
                <div data-aos="fade-left">
                    <h2 class="text-4xl lg:text-6xl font-accent text-dark mb-8 leading-tight">Perfect Place For An
                        Exceptional Experience</h2>
                    <p class="text-gray-500 mb-10">We provide a unique dining experience where traditional flavors meet
                        modern innovation. Our chefs carefully select the best ingredients.</p>
                    <div class="space-y-6">
                        <div class="flex items-center gap-6 group">
                            <div
                                class="w-16 h-16 rounded-full bg-accent/10 flex items-center justify-center text-accent text-2xl group-hover:bg-accent group-hover:text-white transition">
                                📱</div>
                            <div>
                                <h4 class="font-bold text-dark">Mobile Food Ordering</h4>
                                <p class="text-sm text-gray-400">Order from your phone with ease</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6 group">
                            <div
                                class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary text-2xl group-hover:bg-primary group-hover:text-white transition">
                                🛒</div>
                            <div>
                                <h4 class="font-bold text-dark">Easy Healthy Food</h4>
                                <p class="text-sm text-gray-400">Fresh and healthy ingredients only</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Section -->
    <section class="py-24 bg-gray-50 overflow-hidden">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl lg:text-5xl font-accent mb-16">Choose a Category</h2>
            <div class="flex items-center justify-center gap-12 overflow-x-auto pb-8 mask-fade">
                @foreach(['Burgers', 'Pizzas', 'Drinks', 'Desserts', 'Salads'] as $cat)
                    <div class="min-w-[150px] group cursor-pointer" data-aos="fade-up">
                        <div
                            class="w-32 h-32 mx-auto bg-white rounded-full flex items-center justify-center mb-6 group-hover:bg-primary group-hover:shadow-xl transition shadow-sm border border-gray-100 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=2070"
                                class="w-full h-full object-cover">
                        </div>
                        <span
                            class="font-bold tracking-widest text-xs uppercase text-gray-400 group-hover:text-primary transition">{{ $cat }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section class="py-32">
        <div class="container mx-auto px-6">
            <div
                class="bg-primary rounded-[4rem] p-12 lg:p-20 relative overflow-hidden flex flex-col lg:flex-row items-center gap-20">
                <div class="lg:w-2/3" data-aos="fade-right">
                    <h2 class="text-white text-5xl font-accent mb-12">How We Work</h2>
                    <div class="grid md:grid-cols-3 gap-12">
                        <div class="text-center lg:text-left">
                            <div
                                class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center text-white text-2xl mb-6 mx-auto lg:mx-0">
                                📋</div>
                            <h4 class="text-white font-bold mb-4">Explore Menu</h4>
                            <p class="text-white/60 text-sm">Browse our wide range of delicious burgers and sides.</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <div
                                class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center text-white text-2xl mb-6 mx-auto lg:mx-0">
                                🍔</div>
                            <h4 class="text-white font-bold mb-4">Choose a Dish</h4>
                            <p class="text-white/60 text-sm">Select your favorite masterpiece and customize it.</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <div
                                class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center text-white text-2xl mb-6 mx-auto lg:mx-0">
                                🛵</div>
                            <h4 class="text-white font-bold mb-4">Place Order</h4>
                            <p class="text-white/60 text-sm">Sit back and relax enquanto we bring it to you.</p>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/3 relative" data-aos="zoom-in">
                    <img src="https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=1965"
                        class="w-64 h-64 object-cover rounded-full border-8 border-white/20">
                    <div
                        class="absolute inset-0 border-4 border-dashed border-white/20 rounded-full scale-125 animate-spin-slow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Grid -->
    <section id="menu" class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-5xl font-accent mb-4">Fast Food Menus</h2>
                <div class="w-20 h-2 bg-primary mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $h3MenuItems = \App\Models\MenuItem::where('is_active', true)->orderBy('sort_order')->take(4)->get();
                @endphp
                @if($h3MenuItems->isNotEmpty())
                    @foreach($h3MenuItems as $item)
                        <div class="bg-gray-50 p-6 {{ $radiusClass }} text-center group hover:bg-white hover:shadow-2xl transition duration-500"
                            data-aos="fade-up">
                            <div class="w-32 h-32 mx-auto mb-6 relative">
                                <div
                                    class="absolute inset-0 bg-primary/10 rounded-full scale-125 -z-10 group-hover:scale-150 transition">
                                </div>
                                <img src="{{ \App\Helpers\StorageHelper::getUrl($item->image_path) }}"
                                    class="w-full h-full object-cover rounded-full group-hover:scale-110 transition">
                            </div>
                            <h4 class="font-bold text-lg mb-2 uppercase">{{ $item->name }}</h4>
                            <div class="text-primary font-black text-xl mb-6">${{ number_format($item->base_price, 2) }}</div>
                            <button @click="$dispatch('add-to-cart', { menuItemId: {{ $item->id }} })"
                                class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center mx-auto hover:bg-primary hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                @else
                    @foreach(['Special Burger', 'Classic Pizza', 'Fresh Drink', 'Sweet Cake'] as $item)
                        <div class="bg-gray-50 p-6 {{ $radiusClass }} text-center group hover:bg-white hover:shadow-2xl transition duration-500"
                            data-aos="fade-up">
                            <div class="w-32 h-32 mx-auto mb-6 relative">
                                <div
                                    class="absolute inset-0 bg-primary/10 rounded-full scale-125 -z-10 group-hover:scale-150 transition">
                                </div>
                                <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=1899"
                                    class="w-full h-full object-cover rounded-full group-hover:scale-110 transition">
                            </div>
                            <h4 class="font-bold text-lg mb-2 uppercase">{{ $item }}</h4>
                            <div class="text-primary font-black text-xl mb-6">$15.00</div>
                            <a href="/menu"
                                class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center mx-auto hover:bg-primary hover:text-white transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-24 bg-[#FFFBF0]">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-accent mb-16">We Provide Best Services</h2>
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="grid grid-cols-2 gap-8 text-left" data-aos="fade-right">
                    @foreach(['Takeaway & Delivery', 'Wine & Cocktails', 'Fine Dining', 'Afternoon Tea'] as $svc)
                        <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition">
                            <div class="text-3xl mb-4">🍽️</div>
                            <h4 class="font-bold mb-2">{{ $svc }}</h4>
                            <p class="text-xs text-gray-400">Professional service for your dining satisf
                                action.</p>
                        </div>

                    @endforeach
                </div>
                <div class="relative" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1780"
                        class="w-full h-[600px] object-cover rounded-[10rem] shadow-2xl">
                    <div class="absolute -bottom-10 -right-20 text-[200px] text-primary opacity-5 -z-10 font-accent">
                        FOOD</div>
                </div>
            </div>
        </div>
    </section>



    <!-- Instagram Feed -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6 text-center">
            <div class="flex flex-center justify-center mb-10">
                <div
                    class="w-16 h-16 bg-primary rounded-full flex items-center justify-center text-white text-2xl shadow-lg shadow-primary/30">
                    IG</div>
            </div>

            <h3 class="font-accent text-2xl mb-12">Follow @KingsBurger</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach(['https://images.unsplash.com/photo-1568901346375-23c9450c58cd', 'https://images.unsplash.com/photo-1586816001966-79b736744398', 'https://images.unsplash.com/photo-1550547660-d9450f859349', 'https://images.unsplash.com/photo-1513185158878-8d8c196b8ca8', 'https://images.unsplash.com/photo-1571407970349-bc81e7e96d47'] as $img)
                    <div class="aspect-square rounded-2xl overflow-hidden cursor-pointer group">
                        <img src="{{ $img }}?q=80&w=1000"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark pt-24 pb-12 text-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-20 mb-20 text-center md:text-left">
                <div class="md:col-span-1">
                    <span
                        class="text-3xl font-accent text-primary mb-8 block uppercase tracking-tighter">{{ tenant('name') }}</span>
                    <p class="text-gray-500 text-sm leading-relaxed mb-8">Delivering excellence in every bite. Join us
                        for an unforgettable dining experience.</p>
                </div>
                <div>
                    <h5 class="font-bold mb-8 opacity-40 uppercase tracking-widest text-xs">Navigation</h5>
                    <ul class="space-y-4 text-sm font-bold">
                        <li><a href="/" class="hover:text-primary transition">Home</a></li>
                        <li><a href="/menu" class="hover:text-primary transition">Menu</a></li>
                        <li><a href="/reservations" class="hover:text-primary transition">Bookings</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold mb-8 opacity-40 uppercase tracking-widest text-xs">Get Help</h5>
                    <ul class="space-y-4 text-sm font-bold">
                        <li><a href="#" class="hover:text-primary transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-primary transition">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="bg-white/5 p-10 {{ $containerRadiusClass }} border border-white/10 backdrop-blur-xl">
                    <h5 class="font-bold mb-6 opacity-40 uppercase tracking-widest text-xs">Newsletter</h5>
                    <input type="email" placeholder="Email..."
                        class="w-full bg-white/10 border-none px-6 py-3 {{ $radiusClass }} mb-4 text-sm focus:ring-2 focus:ring-primary text-white">
                    <button
                        class="w-full bg-primary text-white font-black py-3 {{ $radiusClass }} hover:bg-white hover:text-dark transition tracking-widest uppercase">SUBSCRIBE</button>
                </div>
            </div>
            <div
                class="border-t border-white/5 pt-12 text-center text-[10px] font-bold text-gray-600 uppercase tracking-[1em]">
                &copy; {{ date('Y') }} {{ tenant('name') }}
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