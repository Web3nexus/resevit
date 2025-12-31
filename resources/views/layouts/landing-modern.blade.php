<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Resevit'))</title>
    <meta name="description"
        content="@yield('meta_description', 'Advanced Reservation & Management System for Restaurants')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        :root {
            --brand-modern-bg: #0d1117;
            --brand-modern-card: #161b22;
            --brand-modern-border: #30363d;
            --brand-modern-accent: #7d40ff;
            --brand-modern-secondary: #2f81f7;
            --brand-modern-text: #e6edf3;
            --brand-modern-muted: #8b949e;
        }

        body {
            background-color: var(--brand-modern-bg);
            color: var(--brand-modern-text);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Outfit', sans-serif;
        }

        .glow-text {
            text-shadow: 0 0 20px rgba(125, 64, 255, 0.5);
        }

        .gradient-border {
            position: relative;
            background: var(--brand-modern-card);
            border-radius: 12px;
            z-index: 1;
        }

        .gradient-border::before {
            content: "";
            position: absolute;
            top: -1px;
            left: -1px;
            right: -1px;
            bottom: -1px;
            background: linear-gradient(135deg, var(--brand-modern-accent), var(--brand-modern-secondary));
            border-radius: 13px;
            z-index: -1;
            opacity: 0.3;
            transition: opacity 0.3s ease;
        }

        .gradient-border:hover::before {
            opacity: 0.6;
        }

        .mesh-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: radial-gradient(circle at 50% 50%, #1a1b26 0%, #0d1117 100%);
            opacity: 0.5;
        }

        .mesh-gradient {
            position: absolute;
            top: -20%;
            left: -10%;
            width: 70%;
            height: 70%;
            background: radial-gradient(circle, rgba(125, 64, 255, 0.15) 0%, transparent 70%);
            z-index: -1;
            filter: blur(80px);
        }

        .mesh-gradient-2 {
            position: absolute;
            bottom: -20%;
            right: -10%;
            width: 70%;
            height: 70%;
            background: radial-gradient(circle, rgba(47, 129, 247, 0.1) 0%, transparent 70%);
            z-index: -1;
            filter: blur(80px);
        }
    </style>

    @stack('styles')
</head>

<body class="antialiased">
    <div class="mesh-bg"></div>
    <div class="mesh-gradient"></div>
    <div class="mesh-gradient-2"></div>

    <div class="min-h-screen flex flex-col relative z-10">
        <!-- Navigation -->
        <header x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
            :class="{ 'bg-brand-modern-bg/80 border-b border-brand-modern-border': scrolled, 'bg-transparent border-transparent': !scrolled }"
            class="fixed w-full top-0 z-50 transition-all duration-300 backdrop-blur-md">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}"
                            class="text-2xl font-black text-white tracking-tighter flex items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-modern-accent to-brand-modern-secondary flex items-center justify-center">
                                <i class="fa-solid fa-bolt text-white text-xs"></i>
                            </div>
                            <span>RESE<span class="text-brand-modern-accent">VIT</span></span>
                        </a>
                    </div>

                    <div class="hidden md:ml-10 md:flex space-x-8 items-center">
                        @php
                            $currentRoute = Route::currentRouteName();
                            $navItems = [
                                ['route' => 'features', 'label' => 'Features'],
                                ['route' => 'pricing', 'label' => 'Pricing'],
                                ['route' => 'directory.index', 'label' => 'Explore'],
                                ['route' => 'food.index', 'label' => 'Order Food', 'special' => true],
                                ['route' => 'about', 'label' => 'Platform'],
                            ];
                        @endphp

                        @foreach($navItems as $item)
                            <a href="{{ route($item['route']) }}"
                                class="text-sm font-medium transition-colors hover:text-white {{ $currentRoute === $item['route'] ? 'text-white underline underline-offset-8 decoration-brand-modern-accent decoration-2' : 'text-brand-modern-muted' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>

                    <div class="hidden md:flex items-center space-x-4">
                        @guest
                            <a href="{{ route('login') }}"
                                class="text-sm text-brand-modern-muted hover:text-white font-medium transition-colors px-4 py-2">Log
                                In</a>
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center px-6 py-2.5 text-sm font-bold rounded-full text-white bg-white/10 hover:bg-white/20 transition-all border border-white/10">
                                Join the Future
                            </a>
                        @else
                            <a href="{{ route('dashboard.redirect') }}"
                                class="inline-flex items-center px-6 py-2.5 text-sm font-bold rounded-full text-white bg-brand-modern-accent hover:bg-opacity-80 transition-all shadow-[0_0_20px_rgba(125,64,255,0.3)]">
                                Open Dashboard
                            </a>
                        @endguest
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button @click="open = !open" type="button" class="text-brand-modern-muted hover:text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Mobile menu -->
            <div x-show="open" x-transition.opacity
                class="md:hidden bg-brand-modern-bg border-b border-brand-modern-border">
                <div class="px-4 pt-2 pb-6 space-y-1">
                    @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}"
                            class="block px-3 py-3 text-lg font-medium text-brand-modern-muted hover:text-white">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    <div class="mt-6 space-y-4">
                        @guest
                            <a href="{{ route('login') }}"
                                class="block w-full text-center py-3 text-brand-modern-muted hover:text-white border border-brand-modern-border rounded-xl">Log
                                In</a>
                            <a href="{{ route('register') }}"
                                class="block w-full text-center py-3 bg-brand-modern-accent text-white rounded-xl font-bold">Get
                                Started</a>
                        @else
                            <a href="{{ route('dashboard.redirect') }}"
                                class="block w-full text-center py-3 bg-brand-modern-accent text-white rounded-xl font-bold">Dashboard</a>
                        @endguest
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-brand-modern-bg border-t border-brand-modern-border py-20 mt-20 relative overflow-hidden">
            <!-- Background Subtle Glow -->
            <div
                class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-brand-modern-accent/5 to-transparent">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-12">
                    <div class="col-span-1 md:col-span-2">
                        <a href="{{ route('home') }}" class="text-2xl font-black text-white flex items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-modern-accent to-brand-modern-secondary flex items-center justify-center">
                                <i class="fa-solid fa-bolt text-white text-xs"></i>
                            </div>
                            RESEVIT
                        </a>
                        <p class="mt-6 text-brand-modern-muted leading-relaxed max-w-sm">
                            The future of restaurant management is here. We build tools that empower owners and delight
                            diners through intelligent automation.
                        </p>
                        <div class="mt-8 flex space-x-4">
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-brand-modern-card border border-brand-modern-border flex items-center justify-center text-brand-modern-muted hover:text-white hover:border-white transition-all">
                                <i class="fa-brands fa-github text-lg"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-brand-modern-card border border-brand-modern-border flex items-center justify-center text-brand-modern-muted hover:text-white hover:border-white transition-all">
                                <i class="fa-brands fa-twitter text-lg"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-brand-modern-card border border-brand-modern-border flex items-center justify-center text-brand-modern-muted hover:text-white hover:border-white transition-all">
                                <i class="fa-brands fa-linkedin text-lg"></i>
                            </a>
                        </div>
                    </div>

                    @php
                        $settings = \App\Models\PlatformSetting::current();
                        $otherLinks = $settings->getFooterLinks('others');
                    @endphp

                    <div>
                        <h4 class="text-white font-bold mb-6">Legal</h4>
                        <ul class="space-y-4">
                            @foreach(\App\Models\LegalDocument::where('is_published', true)->orderBy('order')->get() as $legalDoc)
                                <li>
                                    <a href="{{ route('legal.show', $legalDoc->slug) }}"
                                        class="text-brand-modern-muted hover:text-brand-modern-accent transition-colors text-sm">
                                        {{ $legalDoc->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-6">Platform</h4>
                        <ul class="space-y-4 text-sm text-brand-modern-muted">
                            @foreach($otherLinks as $link)
                                <li><a href="{{ $link['url'] }}"
                                        class="hover:text-brand-modern-accent transition-colors">{{ $link['label'] }}</a>
                                </li>
                            @endforeach
                            <li><a href="{{ route('directory.index') }}"
                                    class="hover:text-brand-modern-accent transition-colors">Directory</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-6">Explore</h4>
                        <ul class="space-y-4 text-sm text-brand-modern-muted">
                            <li><a href="{{ route('food.index') }}"
                                    class="hover:text-brand-modern-accent transition-colors">Order Food</a></li>
                            <li><a href="{{ route('features') }}"
                                    class="hover:text-brand-modern-accent transition-colors">Full Feature List</a></li>
                            <li><a href="{{ route('faq') }}"
                                    class="hover:text-brand-modern-accent transition-colors">Support FAQ</a></li>
                        </ul>
                    </div>
                </div>

                <div
                    class="mt-20 pt-8 border-t border-brand-modern-border flex flex-col md:flex-row justify-between items-center gap-4 text-brand-modern-muted text-xs">
                    <p>&copy; {{ date('Y') }} Resevit Platform. Architected for peak performance.</p>
                    <div class="flex items-center gap-6">
                        <span>Status: <span class="text-green-500 font-bold flex items-center gap-1 inline-flex"><span
                                    class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> All Systems
                                Operational</span></span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
    <x-cookie-consent />
</body>

</html>