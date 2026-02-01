@php
    $settings = $website->content['settings'] ?? [];
    $primaryColor = $settings['primary_color'] ?? '#FF2E5B';
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
        'full' => 'rounded-full',
    ];

    $radiusClass = $radiusMap[$borderRadius] ?? 'rounded-lg';
    $containerRadiusClass = $radiusMap[$borderRadius === 'none' ? 'none' : '2xl'] ?? 'rounded-2xl';

    $googleFonts = [
        'Inter' => 'Inter:wght@300;400;500;600;700',
        'Outfit' => 'Outfit:wght@300;400;600;700',
        'Playfair Display' => 'Playfair+Display:wght@400;700',
        'Montserrat' => 'Montserrat:wght@300;400;600;700',
    ];
    $fontUrl = $googleFonts[$fontFamily] ?? $googleFonts['Outfit'];
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
                        sans: ['{{ $fontFamily }}', 'sans-serif'],
                        heading: ['{{ $fontFamily }}', 'sans-serif'],
                    },
                    colors: {
                        primary: '{{ $primaryColor }}',
                        accent: '#FFC837',
                        dark: '#111111',
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

<body class="font-sans text-gray-800 antialiased bg-[#F8F9FB]" x-data="{ scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navigation -->
    <nav :class="scrolled ? 'bg-white shadow-sm py-3' : 'bg-transparent py-5'"
        class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <img src="{{ \App\Helpers\StorageHelper::getUrl($website->content['logo'] ?? '') }}" class="h-8 w-auto"
                    alt="Logo" onerror="this.style.display='none'">
                <span
                    class="text-2xl font-black font-heading tracking-tight" :class="scrolled ? 'text-dark' : 'text-white'">{{ $website->content['business_name'] ?? tenant('name') }}</span>
            </a>

            <div class="hidden lg:flex items-center space-x-10 text-sm font-bold uppercase tracking-wider" :class="scrolled ? 'text-dark/80' : 'text-white/80'">
                <a href="#home" class="hover:text-primary transition">Home</a>
                <a href="#menu" class="hover:text-primary transition">Menu</a>
                <a href="#about" class="hover:text-primary transition">About</a>
                <a href="/reservations" class="hover:text-primary transition">Booking</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="/reservations"
                    class="bg-accent text-dark px-8 py-3 {{ $radiusClass }} font-extrabold text-sm hover:shadow-lg transition">BOOK
                    TABLE</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative pt-32 pb-20 lg:pt-48 lg:pb-40 overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <h1 class="text-5xl lg:text-7xl font-black font-heading text-dark leading-tight mb-8">
                        {{ $website->content['hero_title'] ?? 'Best Food for Your Best Moments' }}
                    </h1>
                    <div class="bg-white p-8 {{ $radiusClass }} shadow-xl border border-gray-100 max-w-xl">
                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <label
                                    class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 block">Cuisine</label>
                                <input type="text" placeholder="What are you craving?"
                                    class="w-full bg-gray-50 border-none px-4 py-3 {{ $radiusClass }} text-sm focus:ring-2 focus:ring-primary shadow-inner">
                            </div>
                            <div>
                                <label
                                    class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 block">Specialty</label>
                                <select
                                    class="w-full bg-gray-50 border-none px-4 py-3 {{ $radiusClass }} text-sm focus:ring-2 focus:ring-primary shadow-inner">
                                    <option>Fine Dining</option>
                                    <option>Fast Food</option>
                                    <option>Desserts</option>
                                </select>
                            </div>
                        </div>
                        <a href="/menu"
                            class="block w-full text-center bg-primary text-white font-black py-4 {{ $radiusClass }} hover:bg-opacity-90 transition tracking-widest shadow-lg shadow-primary/30 uppercase">DISCOVER
                            MENU</a>
                    </div>
                </div>

                <div class="relative" data-aos="fade-left">
                    <!-- Red shape accent -->
                    <div
                        class="absolute -right-20 top-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-primary rounded-[4rem] -z-10 rotate-12">
                    </div>
                    <div class="grid grid-cols-2 gap-6 transform -rotate-3 hover:rotate-0 transition duration-1000">
                        <div class="space-y-6">
                            <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=2069"
                                class="w-full aspect-[4/5] object-cover rounded-[3rem] shadow-2xl">
                            <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1780"
                                class="w-full aspect-square object-cover rounded-[3rem] shadow-2xl">
                        </div>
                        <div class="pt-12">
                            <img src="https://images.unsplash.com/photo-1567620905732-2d1ec7bb7445?q=80&w=1980"
                                class="w-full aspect-[4/5] object-cover rounded-[3rem] shadow-2xl">
                        </div>
                    </div>
                    <!-- Food elements -->
                    <div class="absolute -top-10 -right-10 text-8xl opacity-20">🍋</div>
                    <div class="absolute -bottom-10 -left-10 text-8xl opacity-20">🥬</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feel The Taste Section -->
    <section class="py-24 bg-white relative">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="flex gap-6 items-center" data-aos="zoom-in">
                    <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=1974"
                        class="w-2/3 aspect-[4/5] object-cover rounded-[10rem] shadow-2xl">
                    <div class="space-y-6">
                        <img src="https://images.unsplash.com/photo-1534422298391-e4f8c170db06?q=80&w=2070"
                            class="w-40 h-40 object-cover rounded-full shadow-xl">
                        <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=2070"
                            class="w-32 h-32 object-cover rounded-full shadow-xl border-4 border-accent">
                    </div>
                </div>
                <div data-aos="fade-left">
                    <span class="text-primary font-black uppercase tracking-widest text-sm mb-4 block">Welcome to
                        Resevit</span>
                    <h2 class="text-4xl lg:text-6xl font-black font-heading text-dark mb-8 leading-tight">Feel The Taste
                        of Foods</h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-10">
                        {{ $content['taste_description'] ?? 'Our mission is to make healthy eating accessible to everyone. We partner with local organic farms to source the freshest ingredients, ensuring every bite is packed with nutrients and flavor.' }}
                    </p>
                    <div class="flex items-center gap-6">
                        <div class="text-primary text-4xl">📞</div>
                        <div>
                            <div class="text-sm font-bold text-gray-400 uppercase tracking-widest">Booking Phone</div>
                            <div class="text-2xl font-black text-dark">+1 (555) 000-000</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl lg:text-6xl font-black font-heading text-dark mb-4">Delicious Menus</h2>
                <div class="w-20 h-2 bg-accent mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $categories = \App\Models\Category::with(['menuItems' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')->take(5)])->where('is_active', true)->orderBy('sort_order')->take(3)->get();
                @endphp
                @if($categories->isNotEmpty())
                    @foreach($categories as $cat)
                        <div class="bg-white p-8 {{ $containerRadiusClass }} shadow-sm hover:shadow-xl transition group"
                            data-aos="fade-up">
                            <h3
                                class="text-2xl font-black font-heading text-dark mb-10 pb-4 border-b-2 border-gray-100 group-hover:border-primary transition uppercase">
                                {{ $cat->name }}</h3>
                            <div class="space-y-8">
                                @foreach($cat->menuItems as $item)
                                    <div class="flex justify-between items-center group/item cursor-pointer" @click="$dispatch('add-to-cart', { menuItemId: {{ $item->id }} })">
                                        <div>
                                            <h4 class="font-bold text-gray-800 group-hover/item:text-primary transition flex items-center gap-2">
                                                {{ $item->name }}
                                                <span class="opacity-0 group-hover/item:opacity-100 transition text-xs bg-primary/10 text-primary px-2 py-0.5 rounded-full uppercase">Add +</span>
                                            </h4>
                                            <p class="text-xs text-gray-400 italic">{{ Str::limit($item->description, 50) }}</p>
                                        </div>
                                        <span
                                            class="font-black text-primary text-xl">${{ number_format($item->base_price, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach(['Breakfast', 'Lunch', 'Dinner'] as $col)
                        <div class="bg-white p-8 {{ $containerRadiusClass }} shadow-sm hover:shadow-xl transition group"
                            data-aos="fade-up">
                            <h3
                                class="text-2xl font-black font-heading text-dark mb-10 pb-4 border-b-2 border-gray-100 group-hover:border-primary transition uppercase">
                                {{ $col }}</h3>
                            <div class="space-y-8">
                                @foreach([1, 2, 3] as $item)
                                    <div class="flex justify-between items-center group/item">
                                        <div>
                                            <h4 class="font-bold text-gray-800 group-hover/item:text-primary transition">Special
                                                Dish #{{ $item }}</h4>
                                            <p class="text-xs text-gray-400 italic">Fresh ingredients used daily</p>
                                        </div>
                                        <span class="font-black text-primary text-xl">$15.00</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="text-center mt-12">
                <a href="/menu"
                    class="inline-block bg-primary text-white font-black px-12 py-4 {{ $radiusClass }} hover:bg-dark transition shadow-lg uppercase tracking-widest">Order
                    Full Menu</a>
            </div>
        </div>
    </section>

    <!-- Services Banner (Partners) -->
    <section class="py-16 bg-white border-y border-gray-100">
        <div class="container mx-auto px-6 overflow-hidden">
            <p class="text-center text-xs font-black text-gray-300 uppercase tracking-[0.5em] mb-12">Highly Trusted
                Sponsors</p>
            <div
                class="flex flex-wrap justify-center gap-16 items-center opacity-30 grayscale hover:grayscale-0 hover:opacity-100 transition duration-700">
                <img src="https://cdn-icons-png.flaticon.com/512/5977/5977591.png" class="h-12 w-auto">
                <img src="https://cdn-icons-png.flaticon.com/512/5977/5977583.png" class="h-12 w-auto">
                <img src="https://cdn-icons-png.flaticon.com/512/5977/5977575.png" class="h-12 w-auto">
                <img src="https://cdn-icons-png.flaticon.com/512/5977/5977595.png" class="h-12 w-auto">
            </div>
        </div>
    </section>

    <!-- Deal of the Week Section -->
    <section class="py-24 bg-white overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-black font-heading text-dark mb-2">Deal of the Week</h2>
                <div class="w-16 h-1 bg-accent mx-auto"></div>
            </div>

            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div data-aos="fade-right">
                    <span
                        class="bg-primary text-white text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-widest mb-6 inline-block">Flash
                        Offer</span>
                    <h3 class="text-4xl lg:text-5xl font-black font-heading text-dark mb-8 leading-tight">Shroom Bacon
                        Burger</h3>
                    <ul class="grid grid-cols-2 gap-4 mb-10">
                        @foreach(['Natural Meat', 'Fresh Vegetables', 'Special Sauce', 'Best Curd'] as $feat)
                            <li class="flex items-center gap-3 text-sm font-bold text-gray-600">
                                <span class="text-accent text-xl">✓</span> {{ $feat }}
                            </li>
                        @endforeach
                    </ul>
                    <div class="flex items-center gap-8">
                        <div class="text-3xl font-black text-primary">$15.70 <span
                                class="text-sm line-through text-gray-300 ml-2">$19.00</span></div>
                        <button @click="$dispatch('add-to-cart', { menuItemId: {{ \App\Models\MenuItem::where('is_active', true)->first()?->id ?? 0 }} })"
                            class="bg-dark text-white px-8 py-3 {{ $radiusClass }} font-black hover:bg-primary transition shadow-xl uppercase">ORDER
                            NOW</button>
                    </div>
                </div>
                <div class="relative" data-aos="zoom-in">
                    <div
                        class="w-[500px] h-[500px] bg-accent/20 rounded-full absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -z-10 animate-pulse">
                    </div>
                    <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=1899"
                        class="w-full max-w-lg mx-auto transform hover:rotate-6 transition duration-700">
                    <div class="absolute top-10 right-10 flex flex-col gap-4">
                        <div
                            class="w-16 h-16 bg-white rounded-full shadow-lg flex items-center justify-center font-black">
                            20%</div>
                        <div
                            class="w-16 h-16 bg-primary text-white rounded-full shadow-lg flex items-center justify-center text-2xl">
                            🔥</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Private Dining Banner -->
    <section class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">
            <div
                class="bg-white p-12 {{ $containerRadiusClass }} shadow-xl border border-gray-100 grid lg:grid-cols-2 gap-12 items-center">
                <div class="grid grid-cols-2 gap-4">
                    <img src="https://images.unsplash.com/photo-1543007630-9710e4a00a20?q=80&w=1935"
                        class="w-full h-40 object-cover {{ $radiusClass }}">
                    <img src="https://images.unsplash.com/photo-1550966842-28a1ea2c823d?q=80&w=2070"
                        class="w-full h-40 object-cover {{ $radiusClass }}">
                    <img src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?q=80&w=2070"
                        class="col-span-2 w-full h-60 object-cover {{ $radiusClass }}">
                </div>
                <div>
                    <h3 class="text-4xl font-black font-heading text-dark mb-6 leading-tight">Private Events</h3>
                    <p class="text-gray-500 mb-8 italic">We provide the perfect space for your special occasions. From
                        intimate dinners to large celebrations, our team ensures every detail is perfect.</p>
                    <a href="/reservations"
                        class="bg-primary text-white px-8 py-3 {{ $radiusClass }} font-black hover:bg-dark transition shadow-lg shadow-primary/20 uppercase tracking-widest">Book
                        Now</a>
                    <div class="mt-8 pt-8 border-t border-gray-100 flex items-center gap-4">
                        <span class="text-gray-400 font-bold uppercase tracking-widest text-xs">Owner:</span>
                        <span class="font-black text-dark">{{ tenant('owner')?->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Highlighted Features Strip -->
    <section class="py-20 bg-primary relative overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-white" data-aos="fade-right">
                    <span class="text-xs font-black uppercase tracking-[0.3em] mb-4 block opacity-70">Features /
                        Experience</span>
                    <h2 class="text-4xl lg:text-5xl font-black font-heading leading-tight mb-8">Highlighting Its Unique
                        Features and Experiences</h2>
                    <div class="flex gap-6">
                        <div class="bg-accent text-dark p-6 rounded-2xl">
                            <div class="text-3xl font-black mb-1">11 Years</div>
                            <div class="text-xs font-bold uppercase tracking-widest opacity-60">Experience</div>
                        </div>
                        <button class="flex items-center gap-4 group">
                            <span
                                class="w-16 h-16 rounded-full border border-white/30 flex items-center justify-center text-white group-hover:bg-accent group-hover:text-dark transition">▶</span>
                            <span class="font-black tracking-widest text-xs uppercase">Watch Story</span>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?q=80&w=2070"
                        class="w-full h-56 object-cover rounded-[3rem] shadow-xl transform hover:-rotate-3 transition">
                    <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=2070"
                        class="w-full h-56 object-cover rounded-[3rem] shadow-xl mt-12 transform hover:rotate-3 transition">
                </div>
            </div>
        </div>
    </section>

    <!-- Recent News Section -->
    <section id="news" class="py-24 bg-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-black font-heading text-dark mb-16">Recent News</h2>
            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach($content['news'] ?? [
                                ['title' => 'Creamy Chicken with milk', 'img' => 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?q=80&w=2070'],
                                ['title' => 'As Fry Salmon Dish', 'img' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?q=80&w=2070'],
                                ['title' => 'Supporting Food Farmers', 'img' => 'https://images.unsplash.com/photo-1500651230702-0e2d8a49d4ad?q=80&w=2070']
                            ] as $post)
                            <div class="text-left group cursor-pointer" data-aos="fade-up">

                                                       <di
                     v               class="aspect-4/3 rounded-4xl overflow-hidden mb-6 shadow-lg">

                                                            <img src="{{ $post['img'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                </div>
                                <span class="text-primary font-black uppercase tracking-widest text-[10px] mb-2 block">Catering / 12 Oct 2024</span>
                                <h4 class="text-xl font-black font-heading text-dark group-hover:text-primary transition leading-tight">{{ $post['title'] }}</h4>
                            </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Reservation Table Section -->
    <section id="booking" class="py-24 bg-gray-50 overflow-hidden">

                               <div class="container mx-auto px-6">

                                   <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div data-aos="fade-right">

                                               <span class="text-primary font-black uppercase tracking-widest text-sm mb-4 block">Get In Touch</span>
                    <h2 class="text-4xl lg:text-5xl font-black font-heading text-dark mb-12">Reservation Table & En
                            joy Dining</h2>
                    <div class="space-y-8 text-gray-600">
                        <p class="text-lg italic mb-8">We would love to host you and your guests. Please fill out the form to secure your table.</p>
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 bg-accent {{ $radiusClass }} flex items-center justify-center">📞</div>
                            <div>
                                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">Call Us</div>
                                <div class="font-black text-dark">{{ tenant('phone') ?? '+1 (555) 000-000' }}</div>
                            </div>

                                           </div>
                    </div>

                                           </div>

                                           <div class="bg-white p-12 
   {{ $containerRadiusClass }} shadow-xl shadow-gray-200/50" data-aos="fade-left">
                    <div class="grid grid-
             c              ols-2 gap-6 mb-6">
                        <input type="text" placeholder="Your Name" class="w-full bg-gray-50 border-none px-6 py-4 {{ $radiusClass }} shadow-inner focus:ring-2 focus:ring-primary">
                        <input type="email"
                        placeholder="Email" class="w-full bg-gray-50 border-none px-6 py-4 {{ $radiusClass }} shadow-inner focus:ring-2 focus:ring-primary">

                                               <input type="date" class="w-full bg-gray-50 border-none px-6 py-4 {{ $radiusClass }} shadow-inner focus:ring-2 focus:ring-primary">
                        <input type="time" class="w-full bg-gray-50 border-none px-6 py-4 {{ $radiusClass }} shadow-inner focus:ring-2 focus:ring-primary">
                    </div>
                    <a href="/reservations" class="block w-full text-center bg-primary text-white font-black py-4 {{ $radiusClass }} hover:bg-dark transition shadow-lg shadow-primary/20 tracking-widest uppercase">Book a Table</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <footer class="bg-dark pt-24 pb-12 text-white overflow-hidden relative">
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary/20 rounded-full -mr-48 -mt-48 blur-3xl"></div>
        
        <div class="container
                            mx-auto px-6 relative z-10">
            <div class="grid md:grid-cols-4 gap-12 mb-16">
                <div class="md:col-span-1">

                                           <a href="/" class="flex items-center gap-2 mb-8 uppercase">
                        <span class="text-3xl font-black font-heading tracking-tighter">{{ $website->content['business_name'] ?? tenant('name') }}</span>
                    </a>
                    <p class="text-gray-500 text-sm leading-relaxed max-w-xs mb-8">We reinvent the way you experience dining with fresh ingredients and master cooking.</p>
                </div>
                <div>
                    <h5 class="font-black mb-8 uppercase tracking-widest text-xs italic text-primary">About</h5>
                    <ul class="space-y-4 text-gray-400 text-sm font-bold">
                        <li><a href="/" class="hover:text-primary transition">History</a></li>
                        <li><a href="/reservations" class="hover:text-primary transition">Bookings</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-black mb-8 uppercase tracking-widest text-xs italic text-primary">Menu</h5>
                    <ul class="space-y-4 text-gray-400 text-sm font-bold">
                        <li><a href="/menu" class="hover:text-primary transition">Full Menu</a></li>
                    </ul>
               
                        </div>
                     
                  <div class="bg-white/5 backdrop-blur-xl p-10 {{ $containerRadiusClass }} border border-white/10">
                    <h5 class="font-black mb-6 uppercase tracking-widest text-xs italic">Newsletter</h5>
                
                   <input type="email" placeholder="Email..." class="w-full bg-white/10 border-none px-6 py-3 {{ $radiusClass }} mb-4 text-sm placeholder-white/50 focus:ring-2 focus:ring-primary">
                    <button class="w-full bg-primary text-white font-black py-3 {{ $radiusClass }} hover:bg-white hover:text-dark transition">SEND</button>
                </div>
            </div>
            <div class="border-t border-white/5 pt-12 text-center text-[10px] font-black text-gray-500 uppercase tracking-[0.5em]">
                &copy; {{ date('Y') }} {{ $website->content['business_name'] ?? tenant('name') }}. All rights reserved.
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