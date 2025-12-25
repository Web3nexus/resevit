@props(['heading' => null, 'subheading' => null])
<div class="min-h-screen w-full relative flex items-center justify-center bg-gray-900 overflow-hidden font-sans">

    <!-- Background Image & Overlay -->
    <div class="absolute inset-0 z-0">
        <!-- Inspiration image URL from Goldie or similar vibe -->
        <img src="https://images.unsplash.com/photo-1633681926022-84c23e8cb2d6?q=80&w=2574&auto=format&fit=crop"
            alt="Background" class="w-full h-full object-cover opacity-40 transform scale-105" />

        <!-- Gradient Overlay - Navy/Gold tint -->
        <div
            class="absolute inset-0 bg-gradient-to-br from-navy-900/90 via-navy-900/80 to-purple-900/60 mix-blend-multiply">
        </div>
        <div class="absolute inset-0 bg-navy-900/30"></div>
    </div>

    <!-- Main Container -->
    <div
        class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center justify-center lg:justify-between gap-12 lg:gap-20">

        <!-- Hero Content (Left) -->
        <div class="hidden lg:block w-full lg:w-1/2 text-white space-y-6">
            <div
                class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md rounded-full px-4 py-1.5 border border-white/20 mb-4">
                <span class="flex h-2 w-2 rounded-full bg-gold-500 animate-pulse"></span>
                <span class="text-sm font-medium text-gold-500 tracking-wide uppercase">The #1 App for Pros</span>
            </div>

            <h1 class="text-6xl font-extrabold tracking-tight leading-tight drop-shadow-lg">
                Manage your day <br />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-gold-500 to-yellow-300">like a
                    boss.</span>
            </h1>

            <p class="text-xl text-gray-200 leading-relaxed max-w-lg font-light">
                Join 100K+ businesses who trust us to organize their schedule and grow their
                business.
            </p>

            <!-- Social Proof -->
            <div class="pt-8 flex items-center gap-4">
                <div class="flex -space-x-3">
                    <img class="w-10 h-10 rounded-full border-2 border-navy-900 object-cover"
                        src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=64&h=64"
                        alt="" />
                    <img class="w-10 h-10 rounded-full border-2 border-navy-900 object-cover"
                        src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=64&h=64"
                        alt="" />
                    <img class="w-10 h-10 rounded-full border-2 border-navy-900 object-cover"
                        src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=64&h=64"
                        alt="" />
                </div>
                <div class="text-white">
                    <div class="flex items-center text-gold-500">
                        ★★★★★
                    </div>
                    <span class="text-xs text-gray-300 font-medium">Loved by thousands</span>
                </div>
            </div>
        </div>

        <!-- Floating Card (Right/Center) -->
        <div
            class="max-w-lg px-8 w-full bg-white rounded-3xl shadow-2xl overflow-hidden ring-1 ring-white/20 backdrop-blur-sm relative">
            <!-- Card Header -->
            <div class="px-8 pt-10 pb-6 text-center">
                <!-- Fun Logo/Icon Placeholder -->
                <!-- Fun Logo/Icon Placeholder -->
                <div class="mx-auto flex justify-center mb-6">
                    @php
                        $setting = \App\Models\PlatformSetting::current();
                    @endphp
                    @if ($setting && $setting->logo_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($setting->logo_path) }}"
                            alt="{{ config('app.name') }}" class="h-16 w-auto object-contain">
                    @else
                        <div
                            class="mx-auto h-12 w-12 bg-navy-900 rounded-xl flex items-center justify-center shadow-lg text-gold-500 transform -rotate-3 hover:rotate-3 transition-transform duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <h2 class="text-3xl font-bold text-navy-900 tracking-tight">
                    {{ $heading }}
                </h2>
                @if($subheading)
                    <p class="mt-2 text-sm text-gray-500 font-medium">
                        {{ $subheading }}
                    </p>
                @endif
            </div>

            <!-- Form Container -->
            <div class="px-10 pb-10">
                {{ $slot }}
            </div>

            <!-- Footer Decoration -->
            <div class="h-2 absolute bottom-0 left-0 w-full bg-gradient-to-r from-navy-900 via-navy-900 to-gold-500">
            </div>
        </div>
    </div>
</div>