@props(['heading' => null, 'subheading' => null])
<div class="min-h-screen w-full flex flex-col lg:flex-row overflow-hidden font-sans relative bg-navy-900">

    <!-- Global Full-Page Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1633681926022-84c23e8cb2d6?q=80&w=2574&auto=format&fit=crop"
            alt="Background" class="w-full h-full object-cover transform scale-105" />

        <!-- Brand-consistent Gradient/Dark Overlay for readability -->
        <div class="absolute inset-0 bg-navy-900/60 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-linear-to-t from-navy-900 via-navy-900/40 to-transparent"></div>
    </div>

    <!-- Left Side: Hero Content (Transparent Background) -->
    <div class="hidden lg:flex lg:w-1/2 relative z-10 flex-col items-center justify-center p-12 text-white">
        <!-- Hero Content -->
        <div class="w-full max-w-lg space-y-8">
            <div
                class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md rounded-full px-4 py-1.5 border border-white/20">
                <span class="flex h-2 w-2 rounded-full bg-gold-500 animate-pulse"></span>
                <span class="text-sm font-medium text-gold-500 tracking-wide uppercase">The #1 App for Pros</span>
            </div>

            <div class="space-y-4">
                <h1 class="text-6xl font-extrabold tracking-tight leading-tight drop-shadow-2xl text-white">
                    Manage your day <br />
                    <span class="text-transparent bg-clip-text bg-linear-to-r from-gold-500 to-yellow-300">like a
                        boss.</span>
                </h1>

                <p class="text-xl text-gray-200 leading-relaxed font-light drop-shadow-md">
                    Join 100K+ businesses who trust us to organize their schedule and grow their business.
                </p>
            </div>

            <!-- Social Proof -->
            <div class="pt-8 flex items-center gap-6">
                <div class="flex -space-x-3">
                    <img class="w-12 h-12 rounded-full border-2 border-white/20 object-cover"
                        src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=64&h=64"
                        alt="" />
                    <img class="w-12 h-12 rounded-full border-2 border-white/20 object-cover"
                        src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=64&h=64"
                        alt="" />
                    <img class="w-12 h-12 rounded-full border-2 border-white/20 object-cover"
                        src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=64&h=64"
                        alt="" />
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center text-gold-500 text-lg leading-none">
                        ★★★★★
                    </div>
                    <span class="text-sm text-gray-300 font-medium mt-1">Loved by thousands</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side: Form Section (Centered Card on Mobile, Sidebar Card on Desktop) -->
    <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24 relative z-20">

        <div class="relative z-10 mx-auto w-full max-w-md">

            <!-- High-Contrast Form Card -->
            <div
                class="bg-white rounded-3xl p-8 lg:p-12 shadow-[0_30px_60px_rgba(0,0,0,0.5)] border border-gray-100 relative overflow-hidden">
                <!-- Subtle Gradient Accent at the top of the card -->
                <div class="absolute top-0 left-0 w-full h-1 bg-linear-to-r from-gold-500/0 via-gold-500 to-gold-500/0">
                </div>

                <!-- Logo Section -->
                <div class="flex justify-center mb-8">
                    @php
                        $setting = \App\Models\PlatformSetting::current();
                    @endphp
                    @if ($setting && $setting->logo_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($setting->logo_path) }}"
                            alt="{{ config('app.name') }}" class="h-14 w-auto object-contain">
                    @else
                        <div
                            class="h-14 w-14 bg-navy-900 rounded-2xl flex items-center justify-center shadow-xl text-gold-500 transform -rotate-3 border border-gold-500/30">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Header Section -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-extrabold text-navy-900 tracking-tight">
                        {{ $heading }}
                    </h2>
                    @if($subheading)
                        <p class="mt-2 text-base text-gray-600 font-medium">
                            {{ $subheading }}
                        </p>
                    @endif
                </div>

                <div class="form-container text-gray-900">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <!-- Global Footer Accent -->
    <div
        class="h-1.5 absolute bottom-0 left-0 w-full bg-linear-to-r from-gold-500/0 via-gold-500 to-gold-500/0 opacity-60 z-30">
    </div>
</div>