<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['business_name'] ?? 'Resevit' }} - {{ $template->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Playfair+Display:italic,wght@0,400;0,700;1,400&family=Inter:wght@300;400&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                        display: ['Cinzel', 'serif'],
                    },
                    colors: {
                        primary: '#D4AF37', // Metallic Gold
                        dark: '#050505',
                        gold: '#C5A028',
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans text-gray-400 antialiased bg-dark" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navigation -->
    <nav :class="scrolled ? 'bg-dark/95 border-b border-primary/20 py-4' : 'bg-transparent py-8'" class="fixed w-full top-0 z-50 transition-all duration-700">
        <div class="container mx-auto px-10 flex justify-between items-center">
            <a href="#" class="flex flex-col items-center">
                <span class="text-2xl font-display text-primary tracking-[0.3em] font-black">AUREUM</span>
                <span class="text-[8px] text-primary/60 tracking-[1em] -mt-1 uppercase">Fine Dining</span>
            </a>
            
            <div class="hidden lg:flex items-center space-x-12 text-[10px] font-medium uppercase tracking-[0.4em] text-white/50">
                <a href="#home" class="hover:text-primary transition">Introduction</a>
                <a href="#culinary" class="hover:text-primary transition">Culinary</a>
                <a href="#experience" class="hover:text-primary transition">Experience</a>
                <a href="#contact" class="hover:text-primary transition">Contact</a>
            </div>

            <div class="flex items-center gap-8">
                <a href="#booking" class="text-[10px] font-bold uppercase tracking-widest text-primary border border-primary/30 px-6 py-2 rounded-sm hover:bg-primary hover:text-dark transition duration-500">The Table</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative h-screen flex items-center overflow-hidden">
        <div class="absolute inset-0">
            <video autoplay muted loop class="w-full h-full object-cover">
                <source src="https://assets.mixkit.co/videos/preview/mixkit-slow-motion-of-a-dish-being-prepared-4247-large.mp4" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-black/80"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-dark via-transparent to-transparent"></div>
        </div>
        
        <div class="container mx-auto px-10 relative z-10 text-center">
            <div data-aos="fade-up">
                <span class="text-primary font-serif italic text-xl mb-6 block tracking-widest">Est. 2011</span>
                <h1 class="text-white text-6xl lg:text-[7vw] font-display font-black mb-12 tracking-tighter leading-none">THE PINNACLE OF<br><span class="text-primary">LUXURY</span> TASTE</h1>
                <div class="flex justify-center">
                    <div class="w-0.5 h-24 bg-gradient-to-b from-primary to-transparent"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Culinary Excellence -->
    <section id="culinary" class="py-32 bg-dark">
        <div class="container mx-auto px-10">
            <div class="grid lg:grid-cols-2 gap-32 items-center">
                <div data-aos="fade-right">
                    <span class="text-primary font-bold uppercase tracking-[0.5em] text-[10px] mb-6 block">Our Heritage</span>
                    <h2 class="text-white text-5xl font-serif mb-10 leading-tight">Mastering the Art of<br><span class="italic font-normal">Pure Gastronomy.</span></h2>
                    <p class="text-gray-500 text-lg leading-loose mb-12 font-light italic">"At Aureum, we don't just serve food; we orchestrate sensory journeys. Every plate is a canvas, every ingredient a story of excellence sourced from the farthest corners of the globe."</p>
                    <div class="space-y-8">
                        @foreach(['Hand-selected Wagyu Prime', 'Rare Oceanic Treasures', 'Ancestral Herb Collections'] as $f)
                        <div class="flex items-center gap-6 group">
                            <div class="w-12 h-12 rounded-full border border-primary/20 flex items-center justify-center group-hover:bg-primary transition duration-500 group-hover:text-dark">
                                <span class="text-sm">◆</span>
                            </div>
                            <span class="text-white text-xs font-bold uppercase tracking-widest group-hover:text-primary transition">{{ $f }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="relative" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1550966842-28a1ea2c823d?q=80&w=2070" class="w-full h-[600px] object-cover rounded-sm grayscale hover:grayscale-0 transition duration-1000 border border-primary/10 p-4">
                    <div class="absolute -top-10 -right-10 w-40 h-40 border border-primary/20 rounded-full animate-spin-slow"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Menu (Elegant List) -->
    <section class="py-32 bg-[#080808] border-y border-primary/5">
        <div class="container mx-auto px-10 text-center">
            <h2 class="text-4xl font-display text-white tracking-[0.3em] mb-24 uppercase">The Degustation</h2>
            
            <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-x-24 gap-y-16 text-left">
                @foreach([
                    ['name' => 'Gold Leaf Risotto', 'price' => '€45', 'desc' => 'Acquerello rice / Saffron / 24k gold'],
                    ['name' => 'Cellar Aged Beef', 'price' => '€85', 'desc' => '120 days dry aged / Smoked bone marrow'],
                    ['name' => 'Velvet Lobster', 'price' => '€75', 'desc' => 'Poached in herb butter / Vanilla foam'],
                    ['name' => 'Crystal Dessert', 'price' => '€32', 'desc' => 'Sugar blown pearl / Champagne mousse']
                ] as $item)
                <div class="group cursor-pointer" data-aos="fade-up">
                    <div class="flex justify-between items-end mb-4 group-hover:text-primary transition duration-500">
                        <h4 class="text-2xl font-serif italic text-white group-hover:text-primary">{{ $item['name'] }}</h4>
                        <span class="font-display text-primary text-xl">{{ $item['price'] }}</span>
                    </div>
                    <p class="text-xs uppercase tracking-[0.2em] text-gray-600 group-hover:text-gray-400 transition">{{ $item['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Visual Experience -->
    <section id="experience" class="py-32 bg-dark">
        <div class="container mx-auto px-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach([
                    'https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b',
                    'https://images.unsplash.com/photo-1559339352-11d035aa65de',
                    'https://images.unsplash.com/photo-1544025162-d76694265947',
                    'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b'
                ] as $img)
                <div class="aspect-[3/4] overflow-hidden grayscale hover:grayscale-0 transition duration-1000 group">
                    <img src="{{ $img }}?q=80&w=400" class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark pt-32 pb-12 border-t border-primary/10">
        <div class="container mx-auto px-10">
            <div class="flex flex-col items-center mb-24 text-center">
                <span class="text-4xl font-display text-primary tracking-[0.4em] font-black mb-8">AUREUM</span>
                <div class="flex gap-12 text-[10px] font-bold uppercase tracking-[0.3em] text-white/40">
                    <a href="#" class="hover:text-primary transition">Instagram</a>
                    <a href="#" class="hover:text-primary transition">Reservations</a>
                    <a href="#" class="hover:text-primary transition">Contact</a>
                </div>
            </div>
            
            <div class="grid md:grid-cols-3 gap-12 text-center border-t border-white/5 pt-12">
                <div>
                    <h5 class="text-[10px] font-bold uppercase tracking-widest mb-4 text-primary">Location</h5>
                    <p class="text-sm font-light italic">Avenue Montaigne 12, Paris</p>
                </div>
                <div>
                    <h5 class="text-[10px] font-bold uppercase tracking-widest mb-4 text-primary">Hours</h5>
                    <p class="text-sm font-light italic">Tue - Sat / 18:00 - 00:00</p>
                </div>
                <div>
                    <h5 class="text-[10px] font-bold uppercase tracking-widest mb-4 text-primary">Inquiry</h5>
                    <p class="text-sm font-light italic">concierge@aureum.com</p>
                </div>
            </div>
            
            <div class="mt-24 text-center text-[8px] font-bold text-gray-800 uppercase tracking-[1em]">
                &copy; {{ date('Y') }} {{ $content['business_name'] ?? 'Resevit' }}
            </div>
        </div>
    </footer>

    <script>
        AOS.init({
            duration: 1500,
            once: true,
        });
    </script>
</body>

</html>