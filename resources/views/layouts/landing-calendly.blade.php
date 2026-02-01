<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-scroll-behavior="smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Modern Restaurant Management Platform')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @php
        $platformSettings = \App\Models\PlatformSetting::current();
        $theme = $platformSettings->calendly_theme_settings ?? [];
        // Calendly blue: #006BFF - we'll use this as primary
        $primaryColor = $theme['primary_color'] ?? '#006BFF';
    @endphp

    <style>
        :root {
            --primary-blue:
                {{ $primaryColor }}
            ;
            --text-primary: #0B3558;
            --text-secondary: #476788;
            --bg-light: #F8F9FB;
            --border-color: #E5E9F2;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-color: #FFFFFF;
            line-height: 1.6;
        }

        .calendly-header {
            --header-height: 78px;
            --header-height-mobile: 56px;
        }
    </style>

    @stack('styles')
</head>

<body class="antialiased bg-white text-gray-900" data-testid="marketing-site">
    <!-- Header -->
    <header class="calendly-header fixed w-full top-0 z-50 bg-white border-b border-gray-100" id="header-navigation"
        x-data="{ 
                mobileMenuOpen: false, 
                productOpen: false,
                solutionsOpen: false,
                resourcesOpen: false 
            }">

        <!-- Top Eyebrow (Language/Contact) -->
        <div class="bg-white border-b border-gray-50">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
                <div class="flex items-center justify-between h-10 text-sm">
                    <div class="flex items-center gap-4">
                        <!-- Language Selector Placeholder -->
                        <div class="flex items-center gap-2 text-gray-600 cursor-pointer hover:text-gray-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 16 16">
                                <circle cx="8" cy="8" r="7.3" stroke-width="0.8" />
                                <path
                                    d="M8.15.92c5.35 3.08 5.34 10.81 0 13.91M8.02 1.02c-5.35 3.08-5.34 10.81 0 13.91M15.14 7.91H.96"
                                    stroke-width="0.8" />
                            </svg>
                            <span class="text-xs font-medium">English</span>
                        </div>
                    </div>
                    <div>
                        <a href="#" class="text-xs font-medium text-gray-700 hover:text-gray-900">Talk to sales</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="bg-white" data-testid="header">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
                <div class="flex items-center justify-between h-[78px]">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="flex items-center">
                            @if (!empty($platformSettings->logo_path))
                                <img src="{{ \App\Helpers\StorageHelper::getUrl($platformSettings->logo_path) }}"
                                    alt="{{ config('app.name') }}" class="h-7 w-auto" data-testid="logo">
                            @else
                                <span class="text-2xl font-bold tracking-tight"
                                    style="color: var(--primary-blue)">{{ config('app.name') }}</span>
                            @endif
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <ul class="hidden lg:flex items-center gap-1">
                        <!-- Product -->
                        <li class="relative" @mouseenter="productOpen = true" @mouseleave="productOpen = false">
                            <a href="/features"
                                class="flex items-center gap-1 px-4 py-2 text-[15px] font-medium text-gray-700 hover:text-gray-900 rounded-md hover:bg-gray-50 transition-colors"
                                :aria-expanded="productOpen">
                                Product
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': productOpen }"
                                    fill="none" stroke="currentColor" viewBox="0 0 15 8">
                                    <path d="m1.5 1 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1.25" />
                                </svg>
                            </a>

                            <!-- Mega Menu Dropdown -->
                            <div x-show="productOpen" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute left-0 top-full mt-2 w-[600px] bg-white rounded-2xl shadow-2xl border border-gray-100 p-6"
                                style="display: none;">
                                <div class="grid grid-cols-2 gap-6">
                                    <!-- Column 1 -->
                                    <div class="space-y-2">
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Product
                                        </p>
                                        <a href="#"
                                            class="flex items-start gap-3 p-3 rounded-xl hover:bg-blue-50 transition-colors group">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-[15px] font-semibold text-gray-900 mb-0.5">Reservations
                                                </p>
                                                <p class="text-[13px] text-gray-600">Simplified booking</p>
                                            </div>
                                        </a>
                                        <a href="#"
                                            class="flex items-start gap-3 p-3 rounded-xl hover:bg-purple-50 transition-colors group">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-[15px] font-semibold text-gray-900 mb-0.5">Staff
                                                    Management</p>
                                                <p class="text-[13px] text-gray-600">Team scheduling</p>
                                            </div>
                                        </a>
                                        <a href="#"
                                            class="flex items-start gap-3 p-3 rounded-xl hover:bg-green-50 transition-colors group">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-[15px] font-semibold text-gray-900 mb-0.5">Analytics</p>
                                                <p class="text-[13px] text-gray-600">Business insights</p>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Column 2 -->
                                    <div class="space-y-2">
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">
                                            Platform</p>
                                        <a href="#"
                                            class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-[15px] font-semibold text-gray-900 mb-0.5">Integrations
                                                </p>
                                            </div>
                                        </a>
                                        <a href="#"
                                            class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-[15px] font-semibold text-gray-900 mb-0.5">Mobile app</p>
                                            </div>
                                        </a>
                                        <a href="#"
                                            class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-[15px] font-semibold text-gray-900 mb-0.5">Security</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Solutions -->
                        <li class="relative" @mouseenter="solutionsOpen = true" @mouseleave="solutionsOpen = false">
                            <a href="/solutions"
                                class="flex items-center gap-1 px-4 py-2 text-[15px] font-medium text-gray-700 hover:text-gray-900 rounded-md hover:bg-gray-50 transition-colors">
                                Solutions
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': solutionsOpen }"
                                    fill="none" stroke="currentColor" viewBox="0 0 15 8">
                                    <path d="m1.5 1 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1.25" />
                                </svg>
                            </a>

                            <div x-show="solutionsOpen" x-transition
                                class="absolute left-0 top-full mt-2 w-[500px] bg-white rounded-2xl shadow-2xl border border-gray-100 p-6"
                                style="display: none;">
                                <div class="space-y-2">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">By business
                                        type</p>
                                    <a href="#"
                                        class="flex items-start gap-3 p-3 rounded-xl hover:bg-blue-50 transition-colors">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-[15px] font-semibold text-gray-900 mb-0.5">Restaurants</p>
                                            <p class="text-[13px] text-gray-600">Full-service dining</p>
                                        </div>
                                    </a>
                                    <a href="#"
                                        class="flex items-start gap-3 p-3 rounded-xl hover:bg-orange-50 transition-colors">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-[15px] font-semibold text-gray-900 mb-0.5">Cafes & Coffee
                                                Shops</p>
                                            <p class="text-[13px] text-gray-600">Quick service</p>
                                        </div>
                                    </a>
                                    <a href="#"
                                        class="flex items-start gap-3 p-3 rounded-xl hover:bg-purple-50 transition-colors">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-[15px] font-semibold text-gray-900 mb-0.5">Bars & Nightlife
                                            </p>
                                            <p class="text-[13px] text-gray-600">Event venues</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>

                        <!-- Resources -->
                        <li class="relative" @mouseenter="resourcesOpen = true" @mouseleave="resourcesOpen = false">
                            <a href="/resources"
                                class="flex items-center gap-1 px-4 py-2 text-[15px] font-medium text-gray-700 hover:text-gray-900 rounded-md hover:bg-gray-50 transition-colors">
                                Resources
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': resourcesOpen }"
                                    fill="none" stroke="currentColor" viewBox="0 0 15 8">
                                    <path d="m1.5 1 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1.25" />
                                </svg>
                            </a>

                            <div x-show="resourcesOpen" x-transition
                                class="absolute left-0 top-full mt-2 w-[400px] bg-white rounded-2xl shadow-2xl border border-gray-100 p-6"
                                style="display: none;">
                                <div class="space-y-2">
                                    <a href="#"
                                        class="flex items-start gap-3 p-3 rounded-xl hover:bg-blue-50 transition-colors">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-[15px] font-semibold text-gray-900 mb-0.5">Help Center</p>
                                            <p class="text-[13px] text-gray-600">Get support</p>
                                        </div>
                                    </a>
                                    <a href="#"
                                        class="flex items-start gap-3 p-3 rounded-xl hover:bg-green-50 transition-colors">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-[15px] font-semibold text-gray-900 mb-0.5">Blog</p>
                                            <p class="text-[13px] text-gray-600">Latest updates</p>
                                        </div>
                                    </a>
                                    <a href="#"
                                        class="flex items-start gap-3 p-3 rounded-xl hover:bg-purple-50 transition-colors">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-[15px] font-semibold text-gray-900 mb-0.5">API Docs</p>
                                            <p class="text-[13px] text-gray-600">For developers</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>

                        <!-- Pricing -->
                        <li>
                            <a href="{{ route('pricing') }}"
                                class="block px-4 py-2 text-[15px] font-medium text-gray-700 hover:text-gray-900 rounded-md hover:bg-gray-50 transition-colors">
                                Pricing
                            </a>
                        </li>
                    </ul>

                    <!-- Right Side CTAs -->
                    <ul class="hidden lg:flex items-center gap-3">
                        @guest
                            <li>
                                <a href="{{ route('login') }}"
                                    class="px-5 py-2.5 text-[15px] font-medium text-gray-700 hover:text-gray-900 rounded-full hover:bg-gray-50 transition-colors"
                                    data-testid="login-header">
                                    Log In
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('register') }}"
                                    class="px-6 py-2.5 text-[15px] font-semibold text-white rounded-full transition-all shadow-sm hover:shadow-md"
                                    style="background-color: var(--primary-blue);" data-testid="primary-button">
                                    Get started
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('dashboard.redirect') }}"
                                    class="px-6 py-2.5 text-[15px] font-semibold text-white rounded-full transition-all shadow-sm hover:shadow-md"
                                    style="background-color: var(--primary-blue);">
                                    Dashboard
                                </a>
                            </li>
                        @endguest
                    </ul>

                    <!-- Mobile Menu Button -->
                    <div class="lg:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="p-2 rounded-lg text-gray-700 hover:bg-gray-100" data-testid="hamburger-menu">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-transition class="lg:hidden border-t border-gray-100 bg-white"
                style="display: none;">
                <div class="px-6 py-6 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Product</p>
                        <div class="space-y-2">
                            <a href="#" class="block py-2 text-[15px] font-medium text-gray-700">Reservations</a>
                            <a href="#" class="block py-2 text-[15px] font-medium text-gray-700">Staff Management</a>
                            <a href="#" class="block py-2 text-[15px] font-medium text-gray-700">Analytics</a>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-gray-100 space-y-2">
                        @guest
                            <a href="{{ route('login') }}"
                                class="block w-full px-5 py-3 text-center text-[15px] font-medium text-gray-700 border-2 border-gray-300 rounded-full hover:bg-gray-50">
                                Log In
                            </a>
                            <a href="{{ route('register') }}"
                                class="block w-full px-5 py-3 text-center text-[15px] font-semibold text-white rounded-full"
                                style="background-color: var(--primary-blue);">
                                Get started
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="pt-[118px]" role="main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-16 mt-24">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-6 gap-12">
                <!-- Company -->
                <div class="col-span-2">
                    @if (!empty($platformSettings->logo_path))
                        <img src="{{ \App\Helpers\StorageHelper::getUrl($platformSettings->logo_path) }}"
                            alt="{{ config('app.name') }}" class="h-7 w-auto mb-5">
                    @else
                        <span class="text-2xl font-bold mb-5 block tracking-tight"
                            style="color: var(--primary-blue)">{{ config('app.name') }}</span>
                    @endif
                    <p class="text-[14px] text-gray-600 leading-relaxed max-w-xs">Modern restaurant management platform
                        trusted by thousands worldwide.</p>
                </div>

                <!-- Product -->
                <div>
                    <h4 class="text-[13px] font-bold text-gray-900 mb-4 uppercase tracking-wider">Product</h4>
                    <ul class="space-y-3">
                        <li><a href="#"
                                class="text-[14px] text-gray-600 hover:text-blue-600 transition-colors">Features</a>
                        </li>
                        <li><a href="{{ route('pricing') }}"
                                class="text-[14px] text-gray-600 hover:text-blue-600 transition-colors">Pricing</a></li>
                        <li><a href="#"
                                class="text-[14px] text-gray-600 hover:text-blue-600 transition-colors">Integrations</a>
                        </li>
                    </ul>
                </div>

                <!-- Solutions -->
                <div>
                    <h4 class="text-[13px] font-bold text-gray-900 mb-4 uppercase tracking-wider">Solutions</h4>
                    <ul class="space-y-3">
                        <li><a href="#"
                                class="text-[14px] text-gray-600 hover:text-blue-600 transition-colors">Restaurants</a>
                        </li>
                        <li><a href="#"
                                class="text-[14px] text-gray-600 hover:text-blue-600 transition-colors">Cafes</a></li>
                        <li><a href="#" class="text-[14px] text-gray-600 hover:text-blue-600 transition-colors">Bars</a>
                        </li>
                    </ul>
                </div>

                <!-- Resources -->
                <div>
                    <h4 class="text-[13px] font-bold text-gray-900 mb-4 uppercase tracking-wider">Resources</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-[14px] text-gray-600 hover:text-blue-600 transition-colors">Help
                                Center</a></li>
                        <li><a href="#" class="text-[14px] text-gray-600 hover:text-blue-600 transition-colors">Blog</a>
                        </li>
                        <li><a href="#" class="text-[14px] text-gray-600 hover:text-blue-600 transition-colors">API
                                Docs</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="text-[13px] font-bold text-gray-900 mb-4 uppercase tracking-wider">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('about') }}"
                                class="text-[14px] text-gray-600 hover:text-blue-600 transition-colors">About</a></li>
                        <li><a href="#"
                                class="text-[14px] text-gray-600 hover:text-blue-600 transition-colors">Contact</a></li>
                        @foreach(\App\Models\LegalDocument::where('is_published', true)->orderBy('order')->limit(2)->get() as $doc)
                            <li><a href="{{ route('legal.show', $doc->slug) }}"
                                    class="text-[14px] text-gray-600 hover:text-blue-600 transition-colors">{{ $doc->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div
                class="mt-16 pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-[13px] text-gray-600">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights
                    reserved.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84">
                            </path>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>