<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    @stack('styles')
</head>

<body class="font-sans antialiased text-slate-900 bg-brand-offwhite">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <header class="fixed w-full top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="text-2xl font-extrabold text-brand-primary tracking-tight">
                            RESE<span class="text-brand-accent">VIT</span>
                        </a>
                    </div>

                    <div class="hidden md:ml-10 md:flex space-x-6 items-center">
                        @php
                            $currentRoute = Route::currentRouteName();
                            $navItems = [
                                ['route' => 'features', 'label' => 'Features', 'hasMega' => true],
                                ['route' => 'pricing', 'label' => 'Pricing'],
                                ['route' => 'directory.index', 'label' => 'Directory'],
                                ['route' => 'food.index', 'label' => 'Order Food', 'special' => true],
                                ['route' => 'integrations', 'label' => 'Integrations'],
                                ['route' => 'about', 'label' => 'About'],
                                ['route' => 'faq', 'label' => 'FAQ'],
                            ];
                        @endphp

                        @foreach($navItems as $item)
                            @php
                                $isActive = $currentRoute === $item['route'];
                                $baseClass = 'relative text-sm font-medium transition-colors py-2';
                                $activeClass = $isActive ? 'text-brand-accent border-b-2 border-brand-accent' : 'text-slate-600 hover:text-brand-primary';
                                $specialClass = isset($item['special']) ? 'text-[#FF4F18] hover:text-brand-primary font-bold' : '';
                            @endphp

                            @if(isset($item['hasMega']) && $item['hasMega'])
                                <div class="relative group">
                                    <a href="{{ route($item['route']) }}"
                                        class="{{ $baseClass }} {{ $activeClass }} flex items-center">
                                        {{ $item['label'] }}
                                        <svg class="w-4 h-4 ml-1 transition-transform group-hover:rotate-180" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>

                                    <!-- Mega Menu -->
                                    <div
                                        class="absolute left-0 top-full mt-2 w-screen max-w-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform group-hover:translate-y-0 translate-y-2">
                                        <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 p-6">
                                            <div class="grid grid-cols-2 gap-4">
                                                @php
                                                    $featureCategories = [
                                                        ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Reservations', 'desc' => 'Smart booking system'],
                                                        ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'title' => 'Staff Management', 'desc' => 'Team scheduling'],
                                                        ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Analytics', 'desc' => 'Business insights'],
                                                        ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'title' => 'Menu Builder', 'desc' => 'Digital menus'],
                                                    ];
                                                @endphp
                                                @foreach($featureCategories as $category)
                                                    <a href="{{ route('features') }}"
                                                        class="flex items-start p-3 rounded-lg hover:bg-brand-offwhite transition-colors group/item">
                                                        <div
                                                            class="flex-shrink-0 w-10 h-10 bg-brand-primary/10 rounded-lg flex items-center justify-center group-hover/item:bg-brand-accent transition-colors">
                                                            <svg class="w-5 h-5 text-brand-primary group-hover/item:text-white"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="{{ $category['icon'] }}"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm font-semibold text-brand-primary">
                                                                {{ $category['title'] }}
                                                            </p>
                                                            <p class="text-xs text-slate-500">{{ $category['desc'] }}</p>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                            <div class="mt-4 pt-4 border-t border-slate-100">
                                                <a href="{{ route('features') }}"
                                                    class="text-sm font-semibold text-brand-accent hover:text-brand-primary flex items-center">
                                                    View All Features
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route($item['route']) }}"
                                    class="{{ $baseClass }} {{ $specialClass ?: $activeClass }}">
                                    {{ $item['label'] }}
                                </a>
                            @endif
                        @endforeach
                    </div>

                    <div class="hidden md:flex items-center space-x-3">
                        @guest
                            <a href="{{ route('login') }}"
                                class="text-sm text-slate-600 hover:text-brand-primary font-medium transition-colors px-4 py-2">Log
                                In</a>
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center px-5 py-2 border border-transparent text-sm font-semibold rounded-full text-white bg-brand-primary hover:bg-brand-secondary transition-all shadow-md hover:shadow-lg">
                                Get Started
                            </a>
                        @else
                            <a href="{{ route('dashboard.redirect') }}"
                                class="inline-flex items-center px-5 py-2 border border-transparent text-sm font-semibold rounded-full text-white bg-brand-primary hover:bg-brand-secondary transition-all shadow-md hover:shadow-lg">
                                Go to Dashboard
                            </a>
                        @endguest
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button type="button" class="text-slate-600 hover:text-brand-primary" id="mobile-menu-button">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Mobile menu -->
            <div class="hidden md:hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-b border-slate-100">
                    @foreach($navItems as $item)
                        @php
                            $isActive = $currentRoute === $item['route'];
                            $mobileActiveClass = $isActive ? 'text-brand-accent bg-brand-offwhite border-l-4 border-brand-accent' : 'text-slate-600';
                        @endphp
                        <a href="{{ route($item['route']) }}"
                            class="block px-3 py-2 text-base font-medium {{ $mobileActiveClass }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    <div class="pt-4 pb-3 border-t border-slate-100 mt-2">
                        @guest
                            <a href="{{ route('login') }}"
                                class="block px-3 py-2 text-base font-medium text-slate-600 hover:text-brand-primary">Log
                                In</a>
                            <a href="{{ route('register') }}"
                                class="block px-3 py-2 text-base font-medium text-white bg-brand-primary rounded-lg text-center mt-2">Get
                                Started</a>
                        @else
                            <a href="{{ route('dashboard.redirect') }}"
                                class="block px-3 py-2 text-base font-medium text-white bg-brand-primary rounded-lg text-center">Go
                                to Dashboard</a>
                        @endguest
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow pt-16">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-brand-primary text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                    <div class="col-span-1 md:col-span-1">
                        <a href="{{ route('home') }}" class="text-3xl font-extrabold tracking-tight">
                            RESE<span class="text-brand-accent">VIT</span>
                        </a>
                        <p class="mt-4 text-slate-400 max-w-xs">
                            Modernizing restaurant management with powerful automation and AI-driven insights.
                        </p>
                    </div>

                    @php
                        $settings = \App\Models\PlatformSetting::current();
                        $legalLinks = $settings->getFooterLinks('legal');
                        $otherLinks = $settings->getFooterLinks('others');
                    @endphp

                    <div class="space-y-4">
                        <h4 class="text-white font-bold">{{ __('Legal') }}</h4>
                        <ul class="space-y-2">
                            @foreach(\App\Models\LegalDocument::where('is_published', true)->orderBy('order')->get() as $legalDoc)
                                <li>
                                    <a href="{{ route('legal.show', $legalDoc->slug) }}"
                                        class="text-slate-400 hover:text-brand-accent transition-colors text-sm">
                                        {{ $legalDoc->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-6">Others</h4>
                        <ul class="space-y-4 text-slate-400">
                            @foreach($otherLinks as $link)
                                <li><a href="{{ $link['url'] }}" class="hover:text-brand-accent">{{ $link['label'] }}</a>
                                </li>
                            @endforeach
                            <li><a href="{{ route('filament.influencer.auth.register') }}"
                                    class="hover:text-brand-accent">Influencer Program</a></li>
                            <li><a href="{{ route('directory.index') }}" class="hover:text-brand-accent">Directory</a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-6">Stay Updated</h4>
                        <p class="text-slate-400 mb-4">Subscribe to our newsletter for the latest updates.</p>
                        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex">
                            @csrf
                            <input type="email" name="email" required placeholder="Enter your email"
                                class="flex-grow px-4 py-2 rounded-l-md bg-brand-secondary text-white border-none focus:ring-2 focus:ring-brand-accent">
                            <button type="submit"
                                class="bg-brand-accent text-brand-primary font-bold px-4 py-2 rounded-r-md hover:opacity-90 transition-opacity">
                                Join
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-16 pt-8 border-t border-slate-800 text-center text-slate-500 text-sm">
                    <p>&copy; {{ date('Y') }} Resevit. All rights reserved. Built with precision for the hospitality
                        industry.</p>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
    <script>
        document.getElementById('mobile-menu-button')?.addEventListener('click', function () {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
    <x-cookie-consent />
</body>

</html>