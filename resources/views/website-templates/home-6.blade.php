@php
    $settings = $website->content['settings'] ?? [];
    $primaryColor = $settings['primary_color'] ?? '#D11C1C';
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
    $containerRadiusClass = $radiusMap[$borderRadius === 'none' ? 'none' : '2xl'] ?? 'rounded-2xl';

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
    <link href="https://fonts.googleapis.com/css2?family={{ $fontUrl }}&family=Anton&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['{{ $fontFamily }}', 'sans-serif'],
                        display: ['Anton', 'sans-serif'],
                    },
                    colors: {
                        primary: '{{ $primaryColor }}',
                        secondary: '#FFB800',
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

        .text-outline {
            -webkit-text-stroke: 1px rgba(0, 0, 0, 0.1);
            color: transparent;
        }
    </style>
</head>

<body class="font-sans text-dark antialiased bg-white" x-data="{ scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navigation -->
    <nav :class="scrolled ? 'bg-white shadow-sm py-2' : 'bg-transparent py-4'"
        class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2 uppercase">
                <span
                    class="text-2xl font-display uppercase tracking-wider mix-blend-difference text-white">{{ $website->content['business_name'] ?? tenant('name') }}</span>
            </a>

            <div
                class="hidden lg:flex items-center space-x-10 text-xs font-bold uppercase tracking-[0.2em] mix-blend-difference text-white">
                <a href="#home" class="hover:text-secondary transition">Home</a>
                <a href="#about" class="hover:text-secondary transition">About</a>
                <a href="/menu" class="hover:text-secondary transition">Menu</a>
                <a href="/reservations" class="hover:text-secondary transition">Booking</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="/menu"
                    class="bg-secondary text-dark px-8 py-2.5 {{ $radiusClass }} font-black text-xs hover:scale-105 transition shadow-lg uppercase tracking-widest text-center">ORDER
                    NOW</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-[80vh] bg-primary flex items-center overflow-hidden pt-20">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 text-[200px] font-display">PIZZA</div>
            <div class="absolute bottom-10 right-10 text-[200px] font-display">PIZZA</div>
        </div>

        <div class="max-w-4xl" data-aos="fade-right">
            <h1 class="text-white text-7xl lg:text-[120px] font-display leading-[0.9] mb-10 uppercase italic">
                {{ $website->content['hero_title'] ?? 'AWESOME DELICIOUS FOOD' }}
            </h1>
            <p class="text-white/70 text-lg mb-12 max-w-xl font-bold uppercase tracking-widest italic">
                {{ $website->content['hero_subtitle'] ?? 'Experience excellence with every bite.' }}
            </p>
            <a href="/menu"
                class="inline-block bg-secondary text-dark px-12 py-5 {{ $radiusClass }} font-black tracking-widest hover:bg-white hover:text-primary transition uppercase shadow-2xl">Shop
                Now</a>
        </div>

        <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=2070"
            class="hidden lg:block absolute -right-40 -bottom-40 w-[800px] rotate-12 opacity-80 pointer-events-none">
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="relative" data-aos="zoom-in">
                    <img src="https://randomuser.me/api/portraits/men/1.jpg"
                        class="w-72 h-72 rounded-full object-cover border-[20px] border-gray-50 shadow-2xl mx-auto">
                    <div
                        class="absolute -z-10 top-0 left-0 w-full h-full border-4 border-dashed border-gray-100 rounded-full scale-125">
                    </div>
                </div>
                <div data-aos="fade-left">
                    <span class="text-primary font-bold uppercase tracking-[0.3em] text-xs mb-4 block italic">Our
                        Culinary History</span>
                    <h2 class="text-4xl lg:text-5xl font-display text-dark mb-10 leading-tight uppercase">Welcome To Our
                        Pizza King</h2>
                    <p class="text-gray-500 font-bold mb-10 italic">"Pizza King is a place where food is crafted with
                        passion and served with love. Located in the heart of the city, we've been serving the best
                        pizzas for over a decade."</p>
                    <div class="flex items-center gap-6">
                        <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white">✓
                        </div>
                        <div>
                            <h4 class="font-display text-2xl">Richard Christiana</h4>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest italic">Pizza Master
                                / CEO</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section id="products" class="py-24 bg-pinkish relative overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="text-center mb-20" data-aos="fade-up">
                <span class="text-white/60 font-bold uppercase tracking-widest text-xs mb-4 block italic">Fresh
                    Menu</span>
                <h2 class="text-white text-5xl lg:text-6xl font-display uppercase italic">Explore Our Products</h2>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $h6MenuItems = \App\Models\MenuItem::where('is_active', true)->orderBy('sort_order')->take(8)->get();
                @endphp
                @foreach($h6MenuItems as $item)
                    <div class="bg-secondary p-8 rounded-sm text-center transform hover:-translate-y-2 transition duration-500 shadow-xl group cursor-pointer"
                        data-aos="fade-up" @click="$dispatch('add-to-cart', { menuItemId: {{ $item->id }} })">
                        <div
                            class="w-32 h-32 mx-auto mb-8 rounded-full overflow-hidden shadow-2xl group-hover:scale-110 transition duration-500">
                            <img src="{{ \App\Helpers\StorageHelper::getUrl($item->image_path) ?? 'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=2070' }}"
                                class="w-full h-full object-cover">
                        </div>
                        <h4 class="text-dark font-display text-2xl uppercase mb-2 group-hover:text-primary transition">
                            {{ $item->name }}</h4>
                        <span
                            class="text-primary font-black text-xl italic">${{ number_format($item->base_price, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Daily Special Area -->
    <section class="grid lg:grid-cols-2 min-h-[600px] items-stretch">
        <div class="relative overflow-hidden group">
            <img src="https://images.unsplash.com/photo-1594212699903-ec8a3eca50f5?q=80&w=2071"
                class="w-full h-full object-cover transition duration-1000 group-hover:scale-110">
            <div class="absolute inset-0 bg-dark/20 group-hover:bg-dark/10 transition"></div>
        </div>
        <div class="bg-secondary p-12 lg:p-24 flex flex-col justify-center" data-aos="fade-left">
            <span class="text-primary font-bold uppercase tracking-widest text-xs mb-6 block italic">Today's
                Offer</span>
            <h2 class="text-dark text-6xl lg:text-[80px] font-display leading-[0.8] mb-10 uppercase italic">TODAY'S THE
                HAMBURGER DAY</h2>
            <div class="text-dark font-black text-4xl italic mb-10 uppercase tracking-tighter">ONLY FOR <span
                    class="text-primary">$55.00</span></div>
            <p class="text-dark/60 font-bold mb-12 italic leading-relaxed">Experience our premium handcrafted wagyu beef
                burger with aged cheddar and caramelized onions. Available only today!</p>
            <a href="/menu"
                class="w-fit bg-primary text-white px-12 py-5 {{ $radiusClass }} font-black tracking-widest hover:bg-dark transition shadow-2xl uppercase italic inline-block">Order
                Now</a>
        </div>
    </section>

    <!-- Client Testimonials Outlined Text Background -->
    <section id="testimonials" class="py-32 bg-white relative overflow-hidden">
        <div class="absolute inset-0 flex items-center justify-center opacity-10 pointer-events-none">
            <div class="text-[12vw] font-display uppercase italic text-outline leading-none text-center">
                CLIENTS TESTIMONIALS<br>FOOD REVIEWS
            </div>
        </div>

        <div class="container mx-auto px-6 relative z-10 text-center">
            <div class="max-w-2xl mx-auto" data-aos="fade-up">
                <h4 class="font-display text-3xl mb-8 uppercase italic">"The Best Pizza I've ever had! The thin crust is
                    crispy and the ingredients are remarkably fresh. Service was fast and friendly."</h4>
                <div class="w-16 h-1 bg-primary mx-auto mb-8"></div>
                <div class="flex items-center justify-center gap-4">
                    <img src="https://randomuser.me/api/portraits/women/3.jpg" class="w-12 h-12 rounded-full">
                    <div class="text-left font-bold italic uppercase tracking-tighter">
                        <span class="block text-dark">Robert Jhon</span>
                        <span class="text-gray-400 text-xs">Customer</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary pt-24 pb-12 overflow-hidden text-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-20 mb-20 text-center md:text-left">
                <div class="md:col-span-1">
                    <span
                        class="text-4xl font-display mb-8 block italic">{{ $website->content['business_name'] ?? tenant('name') }}</span>
                    <p class="opacity-70 text-sm font-bold leading-loose">
                        {{ $website->content['footer_about'] ?? 'Crafting authentic culinary experiences since 2011.' }}
                    </p>
                </div>
                <div>
                    <h5 class="font-display text-2xl mb-8 uppercase italic">About</h5>
                    <ul class="space-y-4 text-xs font-bold uppercase tracking-widest opacity-60 italic">
                        <li><a href="#" class="hover:text-secondary transition">History</a></li>
                        <li><a href="#" class="hover:text-secondary transition">Team</a></li>
                        <li><a href="#" class="hover:text-secondary transition">Sponsors</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-display text-2xl mb-8 uppercase italic">Menu</h5>
                    <ul class="space-y-4 text-xs font-bold uppercase tracking-widest opacity-60 italic">
                        <li><a href="#" class="hover:text-secondary transition">Breakfast</a></li>
                        <li><a href="#" class="hover:text-secondary transition">Lunch</a></li>
                        <li><a href="#" class="hover:text-secondary transition">Dinner</a></li>
                    </ul>
                </div>
                <div class="bg-white/10 p-12 rounded-sm backdrop-blur-md">
                    <h5 class="font-display text-2xl mb-6 uppercase italic text-secondary">Newsletter</h5>
                    <input type="email" placeholder="Email..."
                        class="w-full bg-white/20 border-none px-6 py-4 rounded-sm mb-4 text-sm focus:ring-1 focus:ring-white placeholder-white/30 italic">
                    <button
                        class="w-full bg-secondary text-dark font-black py-4 rounded-sm hover:bg-white transition tracking-widest uppercase italic">Send</button>
                </div>
            </div>
            <div
                class="border-t border-white/10 pt-12 text-center text-[10px] font-black opacity-30 uppercase tracking-[1em]">
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