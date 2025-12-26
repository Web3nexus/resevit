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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    @stack('styles')
</head>

<body class="font-sans antialiased text-slate-900 bg-brand-offwhite">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <header class="fixed w-full top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="text-2xl font-extrabold text-brand-primary tracking-tight">
                            RESE<span class="text-brand-accent">VIT</span>
                        </a>
                    </div>

                    <div class="hidden md:ml-10 md:flex space-x-8 items-center">
                        <a href="{{ route('features') }}"
                            class="text-slate-600 hover:text-brand-primary font-medium transition-colors">Features</a>
                        <a href="{{ route('pricing') }}"
                            class="text-slate-600 hover:text-brand-primary font-medium transition-colors">Pricing</a>
                        <a href="{{ route('directory.index') }}"
                            class="text-slate-600 hover:text-brand-primary font-medium transition-colors">Directory</a>
                        <a href="{{ route('integrations') }}"
                            class="text-slate-600 hover:text-brand-primary font-medium transition-colors">Integrations</a>
                        <a href="{{ route('about') }}"
                            class="text-slate-600 hover:text-brand-primary font-medium transition-colors">About</a>
                        <a href="{{ route('faq') }}"
                            class="text-slate-600 hover:text-brand-primary font-medium transition-colors">FAQ</a>
                    </div>

                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('login') }}"
                            class="text-slate-600 hover:text-brand-primary font-medium transition-colors">Log In</a>
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-semibold rounded-full text-white bg-brand-primary hover:bg-brand-secondary transition-all shadow-md hover:shadow-lg">
                            Get Started
                        </a>
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
                    <a href="{{ route('features') }}"
                        class="block px-3 py-2 text-base font-medium text-slate-600">Features</a>
                    <a href="{{ route('pricing') }}"
                        class="block px-3 py-2 text-base font-medium text-slate-600">Pricing</a>
                    <a href="{{ route('directory.index') }}"
                        class="block px-3 py-2 text-base font-medium text-slate-600">Directory</a>
                    <a href="{{ route('integrations') }}"
                        class="block px-3 py-2 text-base font-medium text-slate-600">Integrations</a>
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-slate-600">Log
                        In</a>
                    <a href="{{ route('register') }}"
                        class="block px-3 py-2 text-base font-medium text-brand-primary">Get Started</a>
                </div>
            </div>
        </header>

        <main class="flex-grow pt-20">
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
</body>

</html>