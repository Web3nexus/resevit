@php
    $settings = $website->content['settings'] ?? [];
    $primaryColor = $settings['primary_color'] ?? '#FF2E5B';
    $borderRadius = $settings['border_radius'] ?? 'none';
    $fontFamily = $settings['font_family'] ?? 'DM Sans';

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

    $radiusClass = $radiusMap[$borderRadius] ?? 'rounded-none';

    $googleFonts = [
        'Inter' => 'Inter:wght@400;700',
        'Outfit' => 'Outfit:wght@400;600;700',
        'Playfair Display' => 'Playfair+Display:wght@700;900',
        'Montserrat' => 'Montserrat:wght@400;700;900',
        'DM Sans' => 'DM+Sans:wght@400;700',
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
                        dark: '#111111',
                        soft: '#FAF9F6',
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
    <nav :class="scrolled ? 'bg-white shadow-sm py-2' : 'bg-transparent py-4'"
        class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <span :class="scrolled ? 'text-dark' : 'text-white'"
                    class="text-2xl font-bold italic">{{ $website->content['business_name'] ?? tenant('name') }}</span>
            </a>

            <div :class="scrolled ? 'text-dark' : 'text-white/80'"
                class="hidden lg:flex items-center space-x-10 text-sm font-bold uppercase tracking-widest">
                <a href="#home" class="hover:text-primary transition">Home</a>
                <a href="/menu" class="hover:text-primary transition">Menu</a>
                <a href="/reservations" class="hover:text-primary transition">Booking</a>
                <a href="#process" class="hover:text-primary transition">Process</a>
            </div>

            <div class="flex items-center gap-6">
                <a href="/reservations"
                    class="bg-primary text-white px-8 py-2.5 {{ $radiusClass }} font-bold text-xs hover:shadow-lg transition uppercase italic">BOOK
                    NOW</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-[80vh] flex items-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1543353071-873f17a7a088?q=80&w=2070"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/50"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10 text-center">
            <div data-aos="fade-up">
                <h1 class="text-white text-5xl lg:text-7xl font-serif font-black mb-10 leading-tight">
                    {{ $website->content['hero_title'] ?? 'In the perfect space, fantastic food' }}
                </h1>
                <button
                    class="bg-primary text-white px-10 py-5 rounded-sm font-black tracking-widest hover:bg-white hover:text-primary transition uppercase shadow-2xl text-xs">Explore
                    More</button>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section id="process" class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-primary font-bold uppercase tracking-[0.3em] text-[10px] mb-4 block">Quick &
                    Easy</span>
                <h2 class="text-3xl font-serif font-black mb-12">40 Minutes Delivery Process</h2>

                <div class="relative max-w-5xl mx-auto py-12">
                    <!-- Line -->
                    <div class="absolute top-1/2 left-0 w-full h-0.5 bg-gray-100 -translate-y-1/2"></div>
                    <div class="absolute top-1/2 left-0 w-1/3 h-0.5 bg-primary -translate-y-1/2"></div>

                    <div class="relative z-10 grid grid-cols-4 gap-4">
                        @foreach(['Order Your Food', 'Kitchen In Cooking', 'Wait For Delivery', 'Food Is Ready'] as $idx => $step)
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-12 h-12 {{ $idx == 0 ? 'bg-primary text-white' : 'bg-white text-gray-300 border-2 border-gray-100' }} rounded-full flex items-center justify-center font-bold mb-4 shadow-xl">
                                    {{ $idx + 1 }}
                                </div>
                                <span class="text-xs font-bold uppercase tracking-tighter">{{ $step }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Best Sellers -->
    <section id="best-sellers" class="py-24 bg-soft border-y border-gray-100">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-serif font-black mb-12">Best Sellers Menu</h2>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $h9MenuItems = \App\Models\MenuItem::where('is_active', true)->orderBy('sort_order')->take(8)->get();
                @endphp
                @foreach($h9MenuItems as $item)
                    <div class="bg-white p-6 rounded-sm shadow-sm hover:shadow-xl transition group cursor-pointer"
                        data-aos="fade-up" @click="$dispatch('add-to-cart', { menuItemId: {{ $item->id }} })">
                        <div class="aspect-square overflow-hidden mb-6 relative">
                            <img src="{{ \App\Helpers\StorageHelper::getUrl($item->image_path) ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=400' }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            <div
                                class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                                <span
                                    class="bg-white text-primary px-4 py-2 rounded-full font-black text-[10px] uppercase tracking-widest shadow-xl">Add
                                    +</span>
                            </div>
                        </div>
                        <h4 class="font-bold text-sm mb-2 group-hover:text-primary transition">{{ $item->name }}</h4>
                        <span class="text-primary font-black text-lg">${{ number_format($item->base_price, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Photo Mosaic -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 h-[600px]">
                <div class="col-span-2 row-span-2 relative group overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                </div>
                <div class="relative group overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                </div>
                <div class="relative group overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                </div>
                <div class="col-span-2 relative group overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1574071318508-1cdbad80ad50"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                </div>
            </div>
        </div>
    </section>

    <!-- Flash Sale Area -->
    <section id="flash-sale" class="py-24 bg-primary text-white overflow-hidden relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-serif font-black mb-4 uppercase italic">Flash Sale</h2>
                <div class="flex justify-center gap-4 text-xs font-bold uppercase tracking-widest opacity-60">
                    <span>Breakfast</span>
                    <span>Daily Deal</span>
                    <span>Online Use</span>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                @foreach(['Bacon Burger', 'Thin Italian Pizza', 'Veggie Fresh Salad'] as $sale)
                    <div class="bg-white p-8 rounded-sm text-dark grid grid-cols-2 gap-6 items-center shadow-2xl"
                        data-aos="zoom-in">
                        <div>
                            <h4 class="font-bold text-sm mb-4 leading-tight">{{ $sale }}</h4>
                            <div class="text-primary font-black text-2xl mb-6">$12.00 <span
                                    class="text-[10px] text-gray-300 line-through">$19</span></div>
                            <button
                                class="bg-dark text-white px-6 py-2 rounded-sm text-[10px] font-black uppercase tracking-widest hover:bg-primary transition">Buy
                                Now</button>
                        </div>
                        <img src="https://images.unsplash.com/photo-1550547660-d9450f859349" class="w-full">
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white pt-24 pb-12 overflow-hidden border-t border-gray-100">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-20 mb-20 text-center md:text-left">
                <div class="md:col-span-1">
                    <span
                        class="text-3xl font-serif font-black text-primary mb-8 block italic">{{ $website->content['business_name'] ?? tenant('name') }}</span>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-xs mb-8 italic font-medium">
                        {{ $website->content['footer_about'] ?? 'Fresh, authentic, delivered since 2011.' }}
                    </p>
                </div>
                <div>
                    <h5 class="font-bold text-gray-900 mb-8 uppercase tracking-widest text-xs">Navigation</h5>
                    <ul class="space-y-4 text-gray-400 text-sm font-bold italic">
                        <li><a href="#" class="hover:text-primary transition">History</a></li>
                        <li><a href="#" class="hover:text-primary transition">Careers</a></li>
                        <li><a href="#" class="hover:text-primary transition">Terms</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-gray-900 mb-8 uppercase tracking-widest text-xs">Contact</h5>
                    <ul class="space-y-4 text-gray-400 text-sm font-bold italic">
                        <li>info@feasty.com</li>
                        <li>+1 (555) 000-000</li>
                        <li>123 Food Street, NY</li>
                    </ul>
                </div>
                <div class="bg-primary p-12 rounded-sm text-white">
                    <h5 class="font-black mb-6 uppercase tracking-widest text-xs italic">Newsletter</h5>
                    <input type="email" placeholder="Email..."
                        class="w-full bg-white/10 border-none px-6 py-4 rounded-sm mb-4 text-sm focus:ring-1 focus:ring-white">
                    <button
                        class="w-full bg-dark text-white font-black py-4 rounded-sm hover:bg-white hover:text-primary transition tracking-widest uppercase text-xs">Send</button>
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