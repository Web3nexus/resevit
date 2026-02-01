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
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Syne', 'sans-serif'],
                    },
                    colors: {
                        primary: '#000000',
                        accent: '#FF4D00', // Electric Orange
                        light: '#F5F5F3',
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans text-primary antialiased bg-white" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navigation -->
    <nav :class="scrolled ? 'bg-white/80 backdrop-blur-md py-4' : 'bg-transparent py-8'" class="fixed w-full top-0 z-50 transition-all duration-500">
        <div class="container mx-auto px-10 flex justify-between items-center">
            <a href="#" class="text-3xl font-display font-extrabold tracking-tighter uppercase">Noir.</a>
            
            <div class="hidden lg:flex items-center space-x-12 text-[10px] font-bold uppercase tracking-[0.4em]">
                <a href="#home" class="hover:text-accent transition">Index</a>
                <a href="#menu" class="hover:text-accent transition">Menu</a>
                <a href="#about" class="hover:text-accent transition">Studio</a>
                <a href="#contact" class="hover:text-accent transition">Contact</a>
            </div>

            <div class="flex items-center gap-8">
                <a href="#booking" class="text-[10px] font-bold uppercase tracking-widest border-b-2 border-primary pb-1 hover:text-accent hover:border-accent transition">Book Table</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center pt-20">
        <div class="container mx-auto px-10">
            <div data-aos="fade-up">
                <h1 class="text-[12vw] lg:text-[10vw] font-display font-extrabold leading-[0.85] tracking-tighter uppercase mb-12">
                    REDEFINING<br><span class="text-transparent" style="-webkit-text-stroke: 2px black;">MODERN</span><br>DINING
                </h1>
                <div class="flex flex-col lg:flex-row gap-12 items-end">
                    <p class="max-w-md text-lg leading-relaxed font-light">An avant-garde culinary experience focused on simplicity, seasonal ingredients, and artistic presentation.</p>
                    <div class="flex-1 h-[1px] bg-black/10 hidden lg:block mb-4"></div>
                    <button class="bg-primary text-white px-12 py-5 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-accent transition shadow-2xl">Scroll to Explore</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Large Image Area -->
    <section class="py-10 px-6">
        <div class="rounded-[3rem] overflow-hidden h-[80vh] relative grayscale hover:grayscale-0 transition duration-1000" data-aos="zoom-in">
            <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=2070" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
        </div>
    </section>

    <!-- Menu Section (Minimalist Grid) -->
    <section id="menu" class="py-32 bg-white">
        <div class="container mx-auto px-10">
            <div class="flex flex-col lg:flex-row justify-between items-end mb-24 gap-8">
                <h2 class="text-6xl font-display font-bold uppercase tracking-tighter" data-aos="fade-right">Curated<br>Selections</h2>
                <div class="flex gap-4" data-aos="fade-left">
                    @foreach(['Seasonal', 'Signature', 'Private'] as $cat)
                        <button class="text-[10px] font-bold uppercase tracking-widest px-6 py-2 border border-black/10 rounded-full hover:bg-black hover:text-white transition">{{ $cat }}</button>
                    @endforeach
                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-24">
                @foreach([
                    ['name' => 'Monochrome Scallops', 'price' => '$32', 'desc' => 'Ink reduction / Cauliflower purée'],
                    ['name' => 'Aged Wagyu Strip', 'price' => '$65', 'desc' => 'Truffle jus / Smoked marrow'],
                    ['name' => 'Heirloom Garden', 'price' => '$24', 'desc' => 'Root variations / Herb emulsion'],
                    ['name' => 'Fermented Berries', 'price' => '$18', 'desc' => 'Oat milk sorbet / Basil oil'],
                    ['name' => 'Smoked Trout', 'price' => '$28', 'desc' => 'Dill cream / Rye crumble'],
                    ['name' => 'Charred Octopus', 'price' => '$36', 'desc' => 'Citrus glaze / Squid ink']
                ] as $item)
                <div class="group cursor-pointer" data-aos="fade-up">
                    <div class="aspect-[4/5] bg-light rounded-2xl overflow-hidden mb-8 relative">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=400" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition duration-700">
                    </div>
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-xl font-display font-bold uppercase">{{ $item['name'] }}</h4>
                        <span class="font-bold text-accent">{{ $item['price'] }}</span>
                    </div>
                    <p class="text-sm text-gray-400 font-light">{{ $item['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Values / Quote -->
    <section class="py-32 bg-light">
        <div class="container mx-auto px-10 text-center">
            <div class="max-w-4xl mx-auto" data-aos="fade-up">
                <span class="text-accent font-bold uppercase tracking-[0.5em] text-[10px] mb-12 block">Philosophy</span>
                <h3 class="text-4xl lg:text-5xl font-display font-bold uppercase leading-tight italic">"Simplicity is the ultimate sophistication. We strip away the unnecessary to reveal the essential beauty of every ingredient."</h3>
                <div class="mt-12 flex justify-center items-center gap-4">
                    <div class="w-12 h-[1px] bg-black"></div>
                    <span class="text-xs font-bold uppercase tracking-widest">Executive Chef / Noir</span>
                    <div class="w-12 h-[1px] bg-black"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white pt-32 pb-12 border-t border-black/5">
        <div class="container mx-auto px-10">
            <div class="grid md:grid-cols-4 gap-20 mb-32">
                <div class="md:col-span-2">
                    <span class="text-5xl font-display font-extrabold tracking-tighter uppercase mb-12 block">Noir.</span>
                    <div class="grid grid-cols-2 gap-12">
                        <div>
                            <h5 class="text-[10px] font-bold uppercase tracking-widest mb-6 opacity-40">Locations</h5>
                            <ul class="space-y-4 text-sm font-light">
                                <li>Manhattan, NY</li>
                                <li>Chelsea, London</li>
                                <li>Marais, Paris</li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="text-[10px] font-bold uppercase tracking-widest mb-6 opacity-40">Follow</h5>
                            <ul class="space-y-4 text-sm font-light">
                                <li><a href="#" class="hover:text-accent transition">Instagram</a></li>
                                <li><a href="#" class="hover:text-accent transition">Behance</a></li>
                                <li><a href="#" class="hover:text-accent transition">Vimeo</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <h5 class="text-[10px] font-bold uppercase tracking-widest mb-12 opacity-40">Newsletter</h5>
                    <div class="flex items-end gap-4 border-b border-black pb-4">
                        <input type="email" placeholder="Your Email Address" class="bg-transparent border-none w-full text-2xl font-display uppercase tracking-tight focus:ring-0 placeholder:text-black/10">
                        <button class="text-accent text-3xl">→</button>
                    </div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-center gap-8 text-[8px] font-bold uppercase tracking-[0.4em] opacity-30">
                <span>&copy; {{ date('Y') }} {{ $content['business_name'] ?? 'Resevit' }}</span>
                <div class="flex gap-12">
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        AOS.init({
            duration: 1200,
            once: true,
        });
    </script>
</body>

</html>
