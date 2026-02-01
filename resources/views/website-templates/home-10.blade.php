@php
    $settings = $website->content['settings'] ?? [];
    $primaryColor = $settings['primary_color'] ?? '#FF8A00';
    $borderRadius = $settings['border_radius'] ?? 'none'; // Default for Luxe
    $fontFamily = $settings['font_family'] ?? 'Lato';

    $radiusMap = [
        'none' => 'rounded-none', 'sm' => 'rounded-sm', 'md' => 'rounded-md', 'lg' => 'rounded-lg',
        'xl' => 'rounded-xl', '2xl' => 'rounded-2xl', '3xl' => 'rounded-3xl', 'full' => 'rounded-full',
    ];

    $radiusClass = $radiusMap[$borderRadius] ?? 'rounded-none';
    
    $googleFonts = [
        'Inter' => 'Inter:wght@300;400;700',
        'Outfit' => 'Outfit:wght@300;400;600;700',
        'Playfair Display' => 'Playfair+Display:wght@400;700',
        'Montserrat' => 'Montserrat:wght@300;400;700',
        'Lato' => 'Lato:wght@300;400;700',
    ];
    $fontUrl = $googleFonts[$fontFamily] ?? $googleFonts['Lato'];
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
    <link href="https://fonts.googleapis.com/css2?family={{ $fontUrl }}&family=Marcellus&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['{{ $fontFamily }}', 'sans-serif'],
                        serif: ['Marcellus', 'serif'],
                    },
                    colors: {
                        primary: '{{ $primaryColor }}',
                        dark: '#0A0B0D',
                        grayish: '#1A1C1F',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: '{{ $fontFamily }}', sans-serif; }
    </style>
</head>

<body class="font-sans text-gray-300 antialiased bg-dark" x-data="{ scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navigation -->
    <nav :class="scrolled ? 'bg-dark/95 shadow-xl py-3' : 'bg-transparent py-6'"
        class="fixed w-full top-0 z-50 transition-all duration-300 border-b border-white/5">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <span class="text-2xl font-serif text-white tracking-widest uppercase">{{ $website->content['business_name'] ?? tenant('name') }}</span>
            </a>

            <div
                class="hidden lg:flex items-center space-x-10 text-[10px] font-bold uppercase tracking-[0.3em] text-white/70">
                <a href="#home" class="hover:text-primary transition">Home</a>
                <a href="#about" class="hover:text-primary transition">About</a>
                <a href="/menu" class="hover:text-primary transition">Menu</a>
                <a href="/reservations" class="hover:text-primary transition">Booking</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="/reservations"
                    class="bg-primary text-white px-8 py-2 {{ $radiusClass }} font-bold text-[10px] hover:bg-white hover:text-dark transition tracking-widest uppercase shadow-lg shadow-primary/20">RESERVE</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative h-screen bg-dark flex items-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1517248135467-4c7ed9d42339?q=80&w=2070"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/70"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10 text-center">
            <div data-aos="fade-up">
                <span class="text-primary font-bold uppercase tracking-[0.5em] text-[10px] mb-6 block">{{ $website->content['hero_badge'] ?? 'Premium Experience' }}</span>
                <h1 class="text-white text-5xl lg:text-8xl font-serif mb-12 tracking-tight">{{ strtoupper($website->content['hero_title'] ?? 'RESERVE YOUR TABLE') }}</h1>
                <div class="w-24 h-0.5 bg-primary/50 mx-auto"></div>
            </div>
        </div>
    </section>

    <!-- Alternating Sections -->
    <section id="about" class="py-24 bg-dark">
        <div class="container mx-auto px-6">
            <!-- Row 1 -->
            <div class="grid lg:grid-cols-2 gap-20 items-center mb-32" data-aos="fade-up">
                <div class="order-2 lg:order-1">
                    <span class="text-primary font-bold uppercase tracking-widest text-[10px] mb-4 block">About
                        Us</span>
                    <h2 class="text-white text-4xl font-serif mb-8 leading-tight">We Invite You to Visit Our Coffee
                        House</h2>
                    <p class="text-gray-500 mb-10 leading-loose">Experience the finest selection of hand-roasted beans
                        from around the world. Our master baristas craft every cup with precision and passion.</p>
                    <button
                        class="bg-primary text-white px-8 py-3 rounded-sm font-bold text-[10px] tracking-widest uppercase hover:bg-white hover:text-dark transition">DISCOVER</button>
                </div>
                <div class="order-1 lg:order-2">
                    <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?q=80&w=2070"
                        class="w-full aspect-video object-cover grayscale hover:grayscale-0 transition duration-1000">
                </div>
            </div>

            <!-- Row 2 -->
            <div class="grid lg:grid-cols-2 gap-20 items-center" data-aos="fade-up">
                <div>
                    <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=2070"
                        class="w-full aspect-video object-cover grayscale hover:grayscale-0 transition duration-1000">
                </div>
                <div>
                    <span class="text-primary font-bold uppercase tracking-widest text-[10px] mb-4 block">Coffee
                        Beans</span>
                    <h2 class="text-white text-4xl font-serif mb-8 leading-tight">Quality Kava Beans</h2>
                    <p class="text-gray-500 mb-10 leading-loose">We source only the highest grade Arabica beans from
                        sustainable farms. Our unique roasting profile ensures a balanced and rich flavor.</p>
                    <button
                        class="bg-primary text-white px-8 py-3 rounded-sm font-bold text-[10px] tracking-widest uppercase hover:bg-white hover:text-dark transition">LEARN
                        MORE</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Us -->
    <section class="py-24 bg-grayish">
        <div class="container mx-auto px-6">
            <div class="text-center mb-20">
                <h2 class="text-white text-4xl font-serif italic mb-4">Why people choose us?</h2>
                <div class="w-12 h-0.5 bg-primary/30 mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                @foreach([
                        ['icon' => '☕', 'title' => 'MENU FOR EVERY TASTE', 'desc' => 'From classic espresso to artisanal brews, we have something for everyone.'],
                        ['icon' => '🌱', 'title' => 'ALWAYS QUALITY BEANS', 'desc' => 'We never compromise on the quality of our beans, sourcing only the best.'],
                        ['icon' => '🎓', 'title' => 'EXPERIENCED BARISTA', 'desc' => 'Our baristas are certified professionals with years of experience.']
                    ] as $feature)
                    <div class="text-center group p-8 hover:bg-dark transition duration-500" data-aos="fade-up">
                        <div class="text-5xl mb-8 opacity-40 group-hover:opacity-100 transition">{{ $feature['icon'] }}</div>
                        <h4 class="text-white font-serif mb-6 tracking-widest uppercase text-sm">{{ $feature['title'] }}</h4>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


                   <!-- Working Hours -->
    <section id="hours" class="py-32 bg-dark relative overflow-hidden">
        <div cla
s               s="absolute inset-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1541167760496-162955ed8a9f?q=80&w=2070" class="w-full h-full object-cover">
        </div>

                               <div class="container mx-auto px-6 relative z-10">
            <div class="bg-
g                       rayish/80 backdrop-blur-xl border border-white/5 p-12 lg:p-24 grid lg:grid-cols-2 gap-20 items-center">

                                       <div>
                    <span class="text-primary font-bold uppercase tracking-widest text-[10px] mb-4 block">Reservation</span>
                    <h2 class="text-white text-5xl font-serif mb-8 leading-tight">Working Hours</h2>
                    <button class="bg-primary text-white px-10 py-4 rounded-sm font-bold text-[10px] tracking-widest uppercase hover:bg-white hover:text-dark transition">BOOK NOW</button>
                </div>
                <div class="space-y-8 font-serif text-xl border-l border-primary/20 pl-12">
                    <div class="flex justify-between items-center text-white">
                        <span>Sunday to Tuesday</span>
                        <span class="text-primary">09:00 AM - 10:00 PM</span>
                    </div>
                    <div class="flex justify-between items-center text-white">
                        <span>Friday to Saturday</span>
                        <span class="text-primary">10:00 AM - 12:00 PM</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-24 bg-dark">
        <div class="container mx-auto px-6">
            <div class="g
                       rid grid-cols-2 md:grid-cols-4 gap-12 text-center">
                @foreach([
                        ['num' => '120+', 'label' => 'COFFEE VARIETY'],
                        ['num' => '570+', 'label' => 'DELIVERED MONTHLY'],
                        ['num' => '1640+', 'label' => 'POSITIVE FEEDBACK'],
                        ['num' => '40+', 'label' => 'AWARDS AND HONORS']
                    ] as $stat)
                    <div data-aos="fade-up">
                        <span class="text-white text-5xl font-serif mb-4 block">{{ $stat['num'] }}</span>
                        <span class="text-gray-500 text-[10px] font-bold uppercase tracking-[0.3em]">{{ $stat['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Menu Grid -->
    <section id="menu" class="py-32 bg-grayish">
        <div class="container mx-auto px-6">

                               <div class="
                       text-center mb-20">
                <span class="text-primary font-bold uppercase tracking-widest text-[10px] mb-4 block">Our 
                           Menu</span>
                <h2 class="text-white text-5xl font-serif mb-4">Explore Our Foods</h2>
                <div class="w-12 h-0.5 bg-primary/30 mx-auto"></div>
            </div>

                           
 
                                 <div class="grid md:grid-cols-3 gap-8">
                @php
                    $h10MenuItems = \App\Models\MenuItem::where('is_active', true)->orderBy('sort_order')->take(3)->get();
                @endphp
                @foreach($h10MenuItems as $item)
                    <div class="bg-dark/50 border border-white/5 p-8 group hover:border-primary/50 transition duration-500" data-aos="fade-up">
                        <div class="aspect-video mb-8 overflow-hidden grayscale group-hover:grayscale-0 transition duration-700">
                            <img src="{{ \App\Helpers\StorageHelper::getUrl($item->image_path) ?? 'https://images.unsplash.com/photo-1541167760496-162955ed8a9f?q=80&w=400' }}" class="w-full h-full object-cover">
                        </div>
                        <h4 class="text-white font-serif text-xl mb-4">{{ $item->name }}</h4>
                        <div class="text-primary font-bold text-lg mb-8">${{ number_format($item->base_price, 2) }}</div>
                        <button @click="$dispatch('add-to-cart', { menuItemId: {{ $item->id }} })" 
                                class="block text-center w-full border border-primary/30 text-primary py-3 rounded-sm font-bold text-[10px] tracking-widest uppercase group-hover:bg-primary group-hover:text-white transition cursor-pointer bg-transparent">BUY NOW</button>
                    </div>
                @endforeach
            </div>

                               </div>

                           </section>

    <!-- Footer -->
    
                           <footer class="bg-dark pt-32 pb-12 border-t border-white/5 overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-20 mb-20">
                <div class="md:col-span-1">
                    <span class="text-3xl font-serif text-white mb-8 block tracking-tighter uppercase italic">LuxeDine</span>
                    <p class="text-gray-500 text-sm leading-relaxed max-w-xs mb-8 italic">Crafting excellence in every cup and every plate since 2011.</p>
                    <div class="flex gap-4">
                        @foreach(['FB', 'TW', 'IG'] as $s)
                            <a href="#" class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center text-gray-500 hover:text-white hover:border-white transition italic text-xs">{{ $s }}</a>
                        @endforeach
                    </div>
                </div>
                <div class="md:col-span-2 grid grid-cols-2 gap-12">
                    <div>
                        <h5 class="font-bold text-white mb-8 uppercase tracking-widest text-[10px]">Quick Links</h5>
                        <ul class="space-y-4 text-gray-500 text-xs font-bold uppercase tracking-tight">
                            <li><a href="#" class="hover:text-primary transition">Our History</a></li>
                            <li><a href="#" class="hover:text-primary transition">Legal Info</a></li>
                            <li><a href="#" class="hover:text-primary transition">Support</a></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-bold text-white mb-8 uppercase tracking-widest text-[10px]">Contact Us</h5>
                        <ul class="space-y-4 text-gray-500 text-xs font-bold italic">
                            <li>hello@luxedine.com</li>
        
                                               <li>+1 (555) 789-0000</li>
                            <li>5th Avenue, NYC</li>
                        </ul>
                    </div>
                
               </div>
                <div class="bg-grayish p-12 border border-white/5">
                    <h5 class="text-white font-serif text-xl mb-6">Newsletter</h5>
                    <div class="flex gap-2">
                        <input type="email" placeholder="Email" class="bg-dark border-none px-4 py-3 rounded-sm w-full focus:ring-1 focus:ring-primary text-xs">
                        <button class="bg-primary text-white p-3 rounded-sm hover:opacity-90 transition">✓</button>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/5 pt-12 text-center text-[8px] font-bold text-gray-700 uppercase tracking-[1em]">
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