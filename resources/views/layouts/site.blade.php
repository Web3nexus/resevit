<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', tenant()->name)</title>

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

<body class="font-sans antialiased text-slate-900 bg-white">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <header class="fixed w-full top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}"
                            class="text-2xl font-extrabold text-brand-primary tracking-tight uppercase">
                            {{ tenant()->name }}
                        </a>
                    </div>

                    <div class="hidden md:ml-10 md:flex space-x-8 items-center">
                        <a href="{{ route('home') }}"
                            class="text-slate-600 hover:text-brand-primary font-medium transition-colors">Home</a>
                        <a href="{{ route('tenant.menu') }}"
                            class="text-slate-600 hover:text-brand-primary font-medium transition-colors">Digital
                            Menu</a>
                        <a href="#reserve"
                            class="inline-flex items-center px-6 py-2.5 bg-brand-primary text-white text-sm font-bold rounded-full hover:bg-brand-secondary transition-all shadow-md">
                            Book Table
                        </a>
                    </div>
                </div>
            </nav>
        </header>

        <main class="flex-grow pt-20">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold mb-4 uppercase tracking-widest">{{ tenant()->name }}</h3>
                    <p class="text-slate-400 mb-8 max-w-md mx-auto">{{ tenant()->description }}</p>
                    <div class="flex justify-center space-x-6">
                        <a href="#" class="text-slate-400 hover:text-white transition-colors"><i
                                class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors"><i
                                class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors"><i
                                class="fab fa-twitter text-xl"></i></a>
                    </div>
                    <hr class="my-8 border-white/5">
                    <p class="text-slate-500 text-sm">&copy; {{ date('Y') }} {{ tenant()->name }}. Powered by Resevit.
                    </p>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>

</html>