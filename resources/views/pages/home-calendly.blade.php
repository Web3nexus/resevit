@extends('layouts.landing-calendly')

@section('title', 'Free Online Restaurant Management Software | ' . config('app.name'))
@section('meta_description', config('app.name') . ' is the modern restaurant management platform that makes "finding time" a breeze. When connecting is easy, your teams can get more done.')

@section('content')

    {{-- Hero Section --}}
    <section class="bg-gradient-to-b from-blue-50 to-white py-20 lg:py-28">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-[56px] lg:text-[72px] font-bold text-gray-900 leading-[1.1] tracking-tight mb-6">
                    Restaurant management made simple
                </h1>
                <p class="text-[20px] lg:text-[24px] text-gray-600 leading-relaxed mb-10 max-w-3xl mx-auto">
                    {{ config('app.name') }} is easy enough for individual restaurants, and powerful enough to meet the
                    needs of enterprise organizations — including 86% of the Fortune 500 companies.
                </p>
                <a href="{{ route('register') }}"
                    class="inline-flex items-center px-8 py-4 text-[17px] font-semibold text-white rounded-full transition-all shadow-lg hover:shadow-xl hover:scale-[1.02] bg-blue-600 hover:bg-blue-700">
                    Sign up for free
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- How It Works Section --}}
    <section class="bg-white py-24 lg:py-32">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-[40px] lg:text-[48px] font-bold text-gray-900 leading-tight tracking-tight mb-5">
                    {{ config('app.name') }} makes scheduling simple
                </h2>
            </div>

            <div class="grid lg:grid-cols-5 gap-12 max-w-6xl mx-auto">
                {{-- Step 1 --}}
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-[18px] font-bold text-gray-900 mb-3">Connect your calendars</h3>
                    <p class="text-[14px] text-gray-600 leading-relaxed">
                        {{ config('app.name') }} connects up to six calendars to automate scheduling with real-time
                        availability.
                    </p>
                </div>

                {{-- Step 2 --}}
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-purple-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-[18px] font-bold text-gray-900 mb-3">Add your availability</h3>
                    <p class="text-[14px] text-gray-600 leading-relaxed">
                        Keep guests informed of your availability. Take control with detailed availability settings,
                        scheduling rules, and buffers.
                    </p>
                </div>

                {{-- Step 3 --}}
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-green-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-[18px] font-bold text-gray-900 mb-3">Connect conferencing tools</h3>
                    <p class="text-[14px] text-gray-600 leading-relaxed">
                        Sync your video conferencing tools and set preferences for in-person meetings or calls.
                    </p>
                </div>

                {{-- Step 4 --}}
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-orange-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-[18px] font-bold text-gray-900 mb-3">Customize your event types</h3>
                    <p class="text-[14px] text-gray-600 leading-relaxed">
                        Choose from pre-built templates or quickly create custom event types for any meeting you need.
                    </p>
                </div>

                {{-- Step 5 --}}
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-pink-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-[18px] font-bold text-gray-900 mb-3">Share your scheduling link</h3>
                    <p class="text-[14px] text-gray-600 leading-relaxed">
                        Easily book reservations by embedding scheduling links on your website, landing pages, or emails.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Integrations Section --}}
    <section class="bg-gray-50 py-24 lg:py-32">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-[40px] lg:text-[48px] font-bold text-gray-900 leading-tight tracking-tight mb-5">
                    Integrate with the tools you already use
                </h2>
            </div>

            <div class="grid grid-cols-4 md:grid-cols-8 gap-8 max-w-5xl mx-auto items-center">
                @php
                    $integrations = ['Zoom', 'Salesforce', 'Google Calendar', 'Slack', 'Microsoft Teams', 'Gmail', 'Outlook', 'Chrome'];
                @endphp
                @foreach($integrations as $integration)
                    <div
                        class="flex items-center justify-center h-20 px-4 bg-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all">
                        <span class="text-[12px] font-semibold text-gray-600 text-center">{{ $integration }}</span>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="#" class="inline-flex items-center text-[16px] font-semibold text-blue-600 hover:text-blue-700">
                    See all integrations
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Features Grid --}}
    <section class="bg-white py-24 lg:py-32">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-[40px] lg:text-[48px] font-bold text-gray-900 leading-tight tracking-tight mb-5">
                    More than a reservation system
                </h2>
                <p class="text-[20px] text-gray-600 max-w-2xl mx-auto">
                    Everything you need to run your restaurant efficiently
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-12 max-w-6xl mx-auto">
                @php
                    $features = [
                        ['icon' => 'calendar', 'title' => 'Smart Reservations', 'description' => 'Automated booking system with real-time table availability and instant confirmations.'],
                        ['icon' => 'users', 'title' => 'Staff Management', 'description' => 'Schedule shifts, manage permissions, and track team performance all in one place.'],
                        ['icon' => 'chart', 'title' => 'Analytics Dashboard', 'description' => 'Track revenue, occupancy rates, and customer trends with detailed insights.'],
                        ['icon' => 'bell', 'title' => 'Automated Reminders', 'description' => 'Reduce no-shows by 50% with SMS and email notifications to your guests.'],
                        ['icon' => 'credit-card', 'title' => 'Payment Processing', 'description' => 'Accept deposits, pre-payments, and process refunds seamlessly.'],
                        ['icon' => 'globe', 'title' => 'Multi-location', 'description' => 'Manage multiple restaurant locations from a single, unified dashboard.'],
                    ];
                @endphp

                @foreach($features as $feature)
                    <div class="group">
                        <div
                            class="w-12 h-12 mb-5 rounded-xl bg-blue-100 flex items-center justify-center transition-all group-hover:scale-110">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-[20px] font-bold text-gray-900 mb-3">{{ $feature['title'] }}</h3>
                        <p class="text-[16px] text-gray-600 leading-relaxed">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Pricing Preview --}}
    <section class="bg-gray-50 py-24 lg:py-32">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-[40px] lg:text-[48px] font-bold text-gray-900 leading-tight tracking-tight mb-5">
                    Pick the perfect plan for your team
                </h2>
                <p class="text-[20px] text-gray-600">
                    Start free, upgrade when you're ready
                </p>
            </div>

            <div class="grid md:grid-cols-4 gap-6 max-w-6xl mx-auto">
                @php
                    $plans = [
                        ['name' => 'Free', 'price' => 'Always free', 'features' => ['1 location', '50 reservations/month', 'Basic features'], 'cta' => 'Sign up', 'highlighted' => false],
                        ['name' => 'Starter', 'price' => '$10', 'period' => 'per seat/month', 'features' => ['2 locations', '500 reservations/month', 'Advanced features', 'Priority support'], 'cta' => 'Sign up', 'highlighted' => false],
                        ['name' => 'Professional', 'price' => '$16', 'period' => 'per seat/month', 'features' => ['5 locations', 'Unlimited reservations', 'All features', 'Phone support'], 'cta' => 'Sign up', 'highlighted' => true],
                        ['name' => 'Enterprise', 'price' => 'Contact us', 'features' => ['Unlimited locations', 'White-label', 'Dedicated support', 'Custom integrations'], 'cta' => 'Contact sales', 'highlighted' => false],
                    ];
                @endphp

                @foreach($plans as $plan)
                    <div
                        class="bg-white rounded-2xl p-8 border-2 {{ $plan['highlighted'] ? 'border-blue-600 shadow-xl' : 'border-gray-200' }} relative flex flex-col h-full">
                        @if($plan['highlighted'])
                            <div
                                class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1.5 rounded-full text-[12px] font-bold text-white uppercase tracking-wider bg-blue-600">
                                Most Popular
                            </div>
                        @endif

                        <div class="mb-8">
                            <h3 class="text-[20px] font-bold text-gray-900 mb-4">{{ $plan['name'] }}</h3>
                            <div class="mb-1">
                                <span class="text-[36px] font-bold text-gray-900">{{ $plan['price'] }}</span>
                            </div>
                            @if(isset($plan['period']))
                                <p class="text-[14px] text-gray-600">{{ $plan['period'] }}</p>
                            @endif
                        </div>

                        <ul class="space-y-3 mb-8 flex-grow">
                            @foreach($plan['features'] as $feature)
                                <li class="flex items-start text-[15px] text-gray-700">
                                    <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7">
                                        </path>
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ route('register') }}"
                            class="block w-full py-3.5 text-center text-[15px] font-semibold rounded-full transition-all {{ $plan['highlighted'] ? 'text-white bg-blue-600 hover:bg-blue-700 shadow-md hover:shadow-lg' : 'text-blue-600 border-2 border-blue-600 hover:bg-blue-600 hover:text-white' }}">
                            {{ $plan['cta'] }}
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('pricing') }}"
                    class="inline-flex items-center text-[16px] font-semibold text-blue-600 hover:text-blue-700">
                    Compare features
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Social Proof --}}
    <section class="bg-white py-24 lg:py-32">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-[40px] lg:text-[48px] font-bold text-gray-900 leading-tight tracking-tight mb-5">
                    Discover how businesses grow with {{ config('app.name') }}
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-16 max-w-5xl mx-auto">
                <div class="text-center">
                    <div class="text-[56px] font-bold mb-3 text-blue-600">169%</div>
                    <p class="text-[18px] text-gray-700 font-medium">Increase in bookings</p>
                </div>
                <div class="text-center">
                    <div class="text-[56px] font-bold mb-3 text-blue-600">160%</div>
                    <p class="text-[18px] text-gray-700 font-medium">More revenue per table</p>
                </div>
                <div class="text-center">
                    <div class="text-[56px] font-bold mb-3 text-blue-600">20%</div>
                    <p class="text-[18px] text-gray-700 font-medium">Reduction in no-shows</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Security --}}
    <section class="bg-gray-50 py-24 lg:py-32">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-[40px] lg:text-[48px] font-bold text-gray-900 leading-tight tracking-tight mb-5">
                    Built to keep your organization secure
                </h2>
            </div>

            <div class="flex flex-wrap justify-center items-center gap-12">
                @php
                    $badges = ['SOC 2', 'GDPR', 'HIPAA', 'ISO 27001', 'PCI DSS'];
                @endphp
                @foreach($badges as $badge)
                    <div
                        class="flex items-center justify-center w-28 h-28 bg-white rounded-full border-2 border-gray-200 hover:border-blue-300 hover:shadow-lg transition-all">
                        <span class="text-[14px] font-bold text-gray-700">{{ $badge }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="bg-white py-24 lg:py-32">
        <div class="max-w-3xl mx-auto px-6 lg:px-8 text-center">
            <h2 class="text-[40px] lg:text-[48px] font-bold text-gray-900 leading-tight tracking-tight mb-6">
                Easy access for easy bookings
            </h2>
            <p class="text-[20px] text-gray-600 mb-10 leading-relaxed">
                Sign up free with Google or Microsoft. Or, sign up manually below.
            </p>
            <a href="{{ route('register') }}"
                class="inline-flex items-center px-8 py-4 text-[17px] font-semibold text-white rounded-full transition-all shadow-lg hover:shadow-xl hover:scale-[1.02] bg-blue-600 hover:bg-blue-700">
                Sign up for free
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3">
                    </path>
                </svg>
            </a>
        </div>
    </section>

@endsection