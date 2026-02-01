@php
    $settings = $website->content['settings'] ?? [];
    $primaryColor = $settings['primary_color'] ?? '#B88E2F';
    $borderRadius = $settings['border_radius'] ?? 'none';
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

    $radiusClass = $radiusMap[$borderRadius] ?? 'rounded-none';

    $googleFonts = [
        'Inter' => 'Inter:wght@400;700',
        'Outfit' => 'Outfit:wght@400;600;700',
        'Playfair Display' => 'Playfair+Display:wght@400;700',
        'Montserrat' => 'Montserrat:wght@400;700;900',
        'Cormorant Garamond' => 'Cormorant+Garamond:wght@400;700',
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
        href="https://fonts.googleapis.com/css2?family={{ $fontUrl }}&family=Cormorant+Garamond:wght@400;700&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['{{ $fontFamily }}', 'sans-serif'],
                        serif: ['"Cormorant Garamond"', 'serif'],
                    },
                    colors: {
                        primary: '{{ $primaryColor }}',
                        dark: '#0D0D0D',
                        grayish: '#1A1A1A',
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

<body class="font-sans text-gray-300 antialiased bg-dark" x-data="{ scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navigation -->
    <nav :class="scrolled ? 'bg-dark/95 shadow-xl py-2' : 'bg-transparent py-4'"
        class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <span
                    class="text-2xl font-serif text-primary italic tracking-tight lowercase">{{ $website->content['business_name'] ?? tenant('name') }}</span>
            </a>

            <div class="hidden lg:flex items-center space-x-8 text-[10px] font-bold uppercase tracking-widest"
                :class="scrolled ? 'text-white/70' : 'text-white/70'">
                <a href="#home" class="hover:text-primary transition">Home</a>
                <a href="/menu" class="hover:text-primary transition">Menu</a>
                <a href="/reservations" class="hover:text-primary transition">Booking</a>
                <a href="#contact" class="hover:text-primary transition">Contact</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="/reservations"
                    class="border border-primary text-primary px-6 py-2 {{ $radiusClass }} font-bold text-[10px] hover:bg-primary hover:text-white transition tracking-widest uppercase italic">Visit
                    Us</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative h-screen bg-dark flex items-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=2070"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/80"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10 text-center">
            <div data-aos="fade-up">
                <div class="mb-8 flex justify-center">
                    <div class="w-32 h-32 border border-primary/30 rounded-full flex items-center justify-center">
                        <span class="text-primary font-serif text-3xl italic">CH</span>
                    </div>
                </div>
                <h1 class="text-white text-6xl lg:text-[100px] font-serif mb-12 italic tracking-tighter">
                    {{ $website->content['hero_title'] ?? 'Coffee House Cafe' }}</h1>
                <div class="flex justify-center gap-6">
                    <a href="/menu"
                        class="bg-primary text-white px-10 py-4 {{ $radiusClass }} font-bold text-[10px] tracking-widest uppercase hover:opacity-90 transition">Our
                        Menu</a>
                    <a href="/reservations"
                        class="bg-white/10 backdrop-blur-md text-white px-10 py-4 {{ $radiusClass }} font-bold text-[10px] tracking-widest uppercase hover:bg-white hover:text-dark transition">Reservation</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Highlight Strip -->
    <section class="py-12 bg-grayish">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach(['Fresh Coffee Beans', 'Best Brew Style', 'Natural Pure Coffee', 'Pure Breast Milk'] as $idx => $title)
                    <div class="bg-dark p-6 border border-white/5 flex items-center gap-4 hover:border-primary/30 transition cursor-pointer"
                        data-aos="fade-up" data-aos-delay="{{ $idx * 100 }}">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">☕
                        </div>
                        <div>
                            <h4 class="text-white text-[10px] font-bold uppercase tracking-tight">{{ $title }}</h4>
                            <span class="text-[8px] text-gray-500 uppercase">View Service</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- History Section -->
    <section id="history" class="py-32 bg-dark">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-24 items-center">
                <div class="grid grid-cols-2 gap-4" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1541167760496-162955ed8a9f?q=80&w=400"
                        class="w-full aspect-square object-cover grayscale hover:grayscale-0 transition duration-1000">
                    <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?q=80&w=400"
                        class="w-full aspect-square object-cover grayscale hover:grayscale-0 transition duration-1000 mt-12">
                </div>
                <div data-aos="fade-left">
                    <span class="text-primary font-bold uppercase tracking-[0.3em] text-[10px] mb-4 block">Our
                        Story</span>
                    <h2 class="text-white text-5xl font-serif italic mb-8">Explore The History Of the Cafe</h2>
                    <p class="text-gray-500 mb-12 leading-relaxed italic">Established in 2011, our cafe has been a
                        sanctuary for coffee lovers. We believe in the ritual of the perfect cup and the community it
                        creates.</p>
                    <div class="grid grid-cols-2 gap-8 mb-12 border-l border-primary/20 pl-8">
                        <div>
                            <span class="text-white text-4xl font-serif mb-2 block">95k</span>
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Happy
                                Visitors</span>
                        </div>
                        <div>
                            <span class="text-white text-4xl font-serif mb-2 block">12+</span>
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Master
                                Baristas</span>
                        </div>
                    </div>
                    <button
                        class="border-b border-primary text-primary font-bold text-[10px] tracking-widest uppercase pb-2 hover:text-white transition">Full
                        Story</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu List -->
    <section id="menu" class="py-32 bg-grayish relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary rounded-full blur-[150px] opacity-10"></div>
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-white text-5xl font-serif italic mb-20">Our Special Menus</h2>

            <div class="max-w-4xl mx-auto space-y-12">
                @php
                    $h11MenuItems = \App\Models\MenuItem::where('is_active', true)->orderBy('sort_order')->take(5)->get();
                @endphp
                @foreach($h11MenuItems as $item)
                    <div class="flex justify-between items-end border-b border-white/5 pb-4 group hover:border-primary/50 transition duration-500 cursor-pointer"
                        data-aos="fade-up" @click="$dispatch('add-to-cart', { menuItemId: {{ $item->id }} })">
                        <div class="text-left">
                            <h4 class="text-white font-serif text-2xl group-hover:text-primary transition">{{ $item->name }}
                            </h4>
                            <p class="text-gray-600 text-[10px] italic">
                                {{ \Illuminate\Support\Str::limit($item->description, 50) }}</p>
                        </div>
                        <span class="text-primary font-bold text-xl">${{ number_format($item->base_price, 2) }}</span>
                    </div>
                @endforeach
            </div>

            <a href="/menu"
                class="inline-block mt-20 bg-primary/10 border border-primary/30 text-primary px-12 py-4 {{ $radiusClass }} font-bold text-[10px] tracking-widest uppercase hover:bg-primary hover:text-white transition italic">View
                All</a>
        </div>
    </section>

    <!-- Barista Grid -->
    <section class="py-24 bg-dark">
        <div class="container mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([1, 2, 3, 4] as $i)
                <div class="aspect-[3/4] overflow-hidden grayscale hover:grayscale-0 transition duration-1000 cursor-pointer shadow-2xl"
                    data-aos="zoom-in" data-aos-delay="{{ $i * 100 }}">
                    <img src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?q=80&w=400"
                        class="w-full h-full object-cover">
                </div>
            @endforeach
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark pt-32 pb-12 border-t border-white/5 overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-20 mb-20 text-center md:text-left">
                <div class="md:col-span-1">
                    <span class="text-3xl font-serif text-primary italic lowercase mb-8 block">coffeeHouse</span>
                    <p class="text-gray-500 text-sm italic leading-relaxed max-w-xs mb-8">Where every bean tells a story
                        of quality and passion since 2011.</p>
                </div>
                <div>
                    <h5 class="font-bold text-white mb-8 uppercase tracking-widest text-[10px]">Explore</h5>
                    <ul class="space-y-4 text-gray-500 text-xs font-bold uppercase tracking-widest">
                        <li><a href="#" class="hover:text-primary transition">History</a></li>
                        <li><a href="#" class="hover:text-primary transition">Menu</a></li>
                        <li><a href="#" class="hover:text-primary transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-white mb-8 uppercase tracking-widest text-[10px]">Contact</h5>
                    <ul class="space-y-4 text-gray-500 text-xs italic font-medium">
                        <li>hello@coffeehouse.com</li>
                        <li>+1 (555) 789-0000</li>
                        <li>99 Brew Lane, SF</li>
                    </ul>
                </div>
                <div class="bg-grayish p-12 border border-white/5">
                    <h5 class="text-white font-serif text-xl mb-6 italic">Newsletter</h5>
                    <div class="flex gap-2">
                        <input type="email" placeholder="Email"
                            class="bg-dark border-none px-4 py-3 rounded-sm w-full focus:ring-1 focus:ring-primary text-xs italic">
                        <button class="bg-primary text-white p-3 rounded-sm hover:opacity-90 transition">✓</button>
                    </div>
                </div>
            </div>
            <div
                class="border-t border-white/5 pt-12 text-center text-[8px] font-bold text-gray-800 uppercase tracking-[1em]">
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