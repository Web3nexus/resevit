@extends('layouts.landing-calendly')

@section('title', 'Pricing - ' . config('app.name'))
@section('meta_description', 'Use ' . config('app.name') . ' for FREE or upgrade to one of our powerful plans.')

@section('content')

{{-- Hero Section --}}
<section class="bg-white pt-32 pb-16">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-8 text-center">
        <h1 class="text-[56px] lg:text-[64px] font-bold text-gray-900 leading-[1.1] tracking-tight mb-6">
            Pick the perfect plan for your team
        </h1>
        <p class="text-[20px] text-gray-600 max-w-2xl mx-auto">
            Start free, upgrade when you're ready. No credit card required.
        </p>
    </div>
</section>

{{-- Pricing Cards --}}
<section class="bg-white pb-24">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-6 max-w-6xl mx-auto">
            @php
                $plans = [
                    ['name' => 'Free', 'price' => 'Always free', 'features' => ['1 location', '50 reservations/month', 'Basic features', 'Email support'], 'cta' => 'Sign up', 'highlighted' => false],
                    ['name' => 'Starter', 'price' => '$10', 'period' => 'per seat/month', 'features' => ['2 locations', '500 reservations/month', 'Advanced features', 'SMS notifications', 'Priority support'], 'cta' => 'Sign up', 'highlighted' => false],
                    ['name' => 'Professional', 'price' => '$16', 'period' => 'per seat/month', 'features' => ['5 locations', 'Unlimited reservations', 'All features', 'Custom branding', 'API access', 'Phone support'], 'cta' => 'Sign up', 'highlighted' => true],
                    ['name' => 'Enterprise', 'price' => 'Contact us', 'features' => ['Unlimited locations', 'White-label solution', 'Dedicated manager', 'Custom integrations', 'SLA guarantee', '24/7 support'], 'cta' => 'Contact sales', 'highlighted' => false],
                ];
            @endphp

            @foreach($plans as $plan)
                <div class="bg-white rounded-2xl p-8 border-2 {{ $plan['highlighted'] ? 'border-gray-900 shadow-2xl' : 'border-gray-200' }} relative flex flex-col h-full">
                    @if($plan['highlighted'])
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1.5 rounded-full text-[12px] font-bold text-white uppercase tracking-wider" style="background-color: var(--primary);">
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
                                <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" style="color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                    
                    <a href="{{ route('register') }}" class="block w-full py-3.5 text-center text-[15px] font-semibold rounded-full transition-all {{ $plan['highlighted'] ? 'text-white shadow-md hover:shadow-lg' : 'text-gray-900 border-2 border-gray-900 hover:bg-gray-900 hover:text-white' }}" style="{{ $plan['highlighted'] ? 'background-color: var(--primary);' : '' }}">
                        {{ $plan['cta'] }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Comparison Table --}}
<section class="bg-gray-50 py-24">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-8">
        <h2 class="text-[40px] font-bold text-gray-900 text-center mb-16 tracking-tight">Compare features</h2>
        
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-5 px-6 font-bold text-[15px] text-gray-900">Features</th>
                        <th class="text-center py-5 px-4 font-bold text-[15px] text-gray-900">Free</th>
                        <th class="text-center py-5 px-4 font-bold text-[15px] text-gray-900">Starter</th>
                        <th class="text-center py-5 px-4 font-bold text-[15px] text-gray-900 bg-gray-50">Professional</th>
                        <th class="text-center py-5 px-4 font-bold text-[15px] text-gray-900">Enterprise</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $features = [
                            ['name' => 'Locations', 'free' => '1', 'starter' => '2', 'pro' => '5', 'enterprise' => 'Unlimited'],
                            ['name' => 'Reservations per month', 'free' => '50', 'starter' => '500', 'pro' => 'Unlimited', 'enterprise' => 'Unlimited'],
                            ['name' => 'Staff accounts', 'free' => '2', 'starter' => '5', 'pro' => '20', 'enterprise' => 'Unlimited'],
                            ['name' => 'Email notifications', 'free' => true, 'starter' => true, 'pro' => true, 'enterprise' => true],
                            ['name' => 'SMS notifications', 'free' => false, 'starter' => true, 'pro' => true, 'enterprise' => true],
                            ['name' => 'Analytics dashboard', 'free' => 'Basic', 'starter' => 'Basic', 'pro' => 'Advanced', 'enterprise' => 'Advanced'],
                            ['name' => 'Custom branding', 'free' => false, 'starter' => false, 'pro' => true, 'enterprise' => true],
                            ['name' => 'API access', 'free' => false, 'starter' => false, 'pro' => true, 'enterprise' => true],
                            ['name' => 'White-label', 'free' => false, 'starter' => false, 'pro' => false, 'enterprise' => true],
                        ];
                    @endphp

                    @foreach($features as $feature)
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                            <td class="py-4 px-6 text-[15px] text-gray-700">{{ $feature['name'] }}</td>
                            @foreach(['free', 'starter', 'pro', 'enterprise'] as $plan)
                                <td class="py-4 px-4 text-center {{ $plan === 'pro' ? 'bg-gray-50/50' : '' }}">
                                    @if(is_bool($feature[$plan]))
                                        @if($feature[$plan])
                                            <svg class="w-5 h-5 mx-auto" style="color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    @else
                                        <span class="text-[14px] text-gray-700">{{ $feature[$plan] }}</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- FAQ Section --}}
<section class="bg-white py-24">
    <div class="max-w-3xl mx-auto px-6 lg:px-8">
        <h2 class="text-[40px] font-bold text-gray-900 text-center mb-16 tracking-tight">Frequently Asked Questions</h2>
        
        <div class="space-y-6">
            @php
                $faqs = [
                    ['q' => 'Can I switch plans later?', 'a' => 'Yes! You can upgrade or downgrade your plan at any time. Changes take effect immediately.'],
                    ['q' => 'Do you offer refunds?', 'a' => 'We offer a 30-day money-back guarantee on all paid plans. No questions asked.'],
                    ['q' => 'What payment methods do you accept?', 'a' => 'We accept all major credit cards, PayPal, and bank transfers for Enterprise plans.'],
                    ['q' => 'Is there a setup fee?', 'a' => 'No setup fees. Ever. You only pay for your monthly subscription.'],
                    ['q' => 'Can I cancel anytime?', 'a' => 'Yes, you can cancel your subscription at any time. No long-term contracts required.'],
                ];
            @endphp

            @foreach($faqs as $faq)
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-[18px] font-bold text-gray-900 mb-3">{{ $faq['q'] }}</h3>
                    <p class="text-[16px] text-gray-600 leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="bg-gray-50 py-24">
    <div class="max-w-3xl mx-auto px-6 lg:px-8 text-center">
        <h2 class="text-[40px] font-bold text-gray-900 mb-6 tracking-tight">
            Easy access for easy bookings
        </h2>
        <p class="text-[20px] text-gray-600 mb-10">
            Sign up free with Google or Microsoft. Or, sign up manually below.
        </p>
        <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 text-[17px] font-semibold text-white rounded-full transition-all shadow-lg hover:shadow-xl hover:scale-[1.02]" style="background-color: var(--primary);">
            Sign up for free
            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
        </a>
    </div>
</section>

@endsection
