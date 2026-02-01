@php
    $settings = $website->content['settings'] ?? [];
    $primaryColor = $settings['primary_color'] ?? '#FF2E5B';
    $borderRadius = $settings['border_radius'] ?? 'lg';
    $fontFamily = $settings['font_family'] ?? 'Inter';

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

    $radiusClass = $radiusMap[$borderRadius] ?? 'rounded-lg';

    $googleFonts = [
        'Inter' => 'Inter:wght@400;500;600;700',
        'Outfit' => 'Outfit:wght@400;600;700',
        'Playfair Display' => 'Playfair+Display:wght@400;700',
        'Montserrat' => 'Montserrat:wght@400;600;700',
    ];
    $fontUrl = $googleFonts[$fontFamily] ?? $googleFonts['Inter'];
@endphp

<div class="min-h-screen bg-white text-gray-900 font-sans" style="font-family: '{{ $fontFamily }}', sans-serif;">
    <style>
        :root {
            --primary-color:
                {{ $primaryColor }}
            ;
        }

        .bg-primary {
            background-color:
                {{ $primaryColor }}
            ;
        }

        .text-primary {
            color:
                {{ $primaryColor }}
            ;
        }

        .border-primary {
            border-color:
                {{ $primaryColor }}
            ;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family={{ $fontUrl }}&display=swap" rel="stylesheet">

    <!-- Hero -->
    <header class="relative h-[400px] bg-cover bg-center"
        style="background-image: url('{{ $website->content['booking_hero_image'] ?? 'https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?q=80&w=2070' }}');">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div
            class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center items-center text-center">
            <h1 class="text-4xl md:text-7xl font-black text-white tracking-tight mb-4 uppercase">
                Book A Table
            </h1>
            <p class="text-xl text-white/70 max-w-2xl italic">
                Join us for an unforgettable culinary experience.
            </p>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-10 pb-20">
        <div class="bg-white p-8 md:p-16 {{ $radiusClass }} shadow-2xl border border-gray-100">
            <div class="text-center mb-12">
                <span class="text-primary font-bold uppercase tracking-[0.3em] text-xs mb-4 block">Reservation</span>
                <h2 class="text-3xl md:text-5xl font-black text-dark uppercase mb-4">Secure Your Spot</h2>
                <p class="text-gray-400 italic">Please fill out the form below to book your table. We'll confirm your
                    reservation via email.</p>
            </div>

            @livewire('public-reservation-form')
        </div>
    </main>
</div>