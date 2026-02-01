@extends('layouts.landing-calendly')

@section('title', 'Create your free account - ' . config('app.name'))

@section('content')

    <section class="bg-gray-50 min-h-screen flex items-center justify-center py-16">
        <div class="w-full max-w-5xl px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                {{-- Left: Form Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-10">
                    {{-- Logo --}}
                    <div class="text-center mb-8">
                        @php $platformSettings = \App\Models\PlatformSetting::current(); @endphp
                        @if (!empty($platformSettings->logo_path))
                            <img src="{{ \App\Helpers\StorageHelper::getUrl($platformSettings->logo_path) }}"
                                alt="{{ config('app.name') }}" class="h-8 w-auto mx-auto mb-6">
                        @else
                            <span class="text-[24px] font-bold mb-6 block tracking-tight"
                                style="color: var(--primary)">{{ config('app.name') }}</span>
                        @endif
                        <h1 class="text-[28px] font-bold text-gray-900 tracking-tight">Create your free account</h1>
                        <p class="text-[15px] text-gray-600 mt-2">No credit card required. Upgrade anytime.</p>
                    </div>

                    {{-- Form --}}
                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <input type="email" name="email" placeholder="Enter your email" required autofocus
                                class="w-full px-4 py-3.5 text-[15px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            @error('email')
                                <p class="mt-2 text-[13px] text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit"
                            class="w-full py-3.5 text-[16px] font-semibold text-white rounded-lg transition-all shadow-sm hover:shadow-md"
                            style="background-color: var(--primary);">
                            Continue with email
                        </button>

                        {{-- Divider --}}
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-[13px]">
                                <span class="px-3 bg-white text-gray-500 uppercase tracking-wider font-medium">OR</span>
                            </div>
                        </div>

                        {{-- Social Signup --}}
                        <button type="button"
                            class="w-full flex items-center justify-center gap-3 px-4 py-3.5 text-[15px] font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4"
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853"
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05"
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                <path fill="#EA4335"
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                            </svg>
                            Continue with Google
                        </button>

                        <button type="button"
                            class="w-full flex items-center justify-center gap-3 px-4 py-3.5 text-[15px] font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all">
                            <svg class="w-5 h-5" viewBox="0 0 23 23">
                                <path fill="#f3f3f3" d="M0 0h23v23H0z" />
                                <path fill="#f35325" d="M1 1h10v10H1z" />
                                <path fill="#81bc06" d="M12 1h10v10H12z" />
                                <path fill="#05a6f0" d="M1 12h10v10H1z" />
                                <path fill="#ffba08" d="M12 12h10v10H12z" />
                            </svg>
                            Continue with Microsoft
                        </button>

                        <p class="text-[13px] text-gray-600 text-center mt-4">
                            Continue with Google or Microsoft to connect your calendar.
                        </p>
                    </form>

                    {{-- Footer Link --}}
                    <p class="text-center mt-8 text-[15px] text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold hover:underline"
                            style="color: var(--primary);">Log in</a>
                    </p>
                </div>

                {{-- Right: Benefits --}}
                <div class="hidden lg:block">
                    <h2 class="text-[32px] font-bold text-gray-900 mb-8 tracking-tight">
                        Explore premium features with your free 14-day Teams plan trial
                    </h2>
                    <ul class="space-y-5">
                        @php
                            $benefits = [
                                'Multi-person and co-hosted meetings',
                                'Round Robin meeting distribution',
                                'Meeting reminders, follow-ups, and notifications',
                                'Connect payment tools like PayPal or Stripe',
                                'Remove ' . config('app.name') . ' branding',
                            ];
                        @endphp
                        @foreach($benefits as $benefit)
                            <li class="flex items-start">
                                <svg class="w-6 h-6 mr-3 flex-shrink-0 mt-0.5" style="color: var(--primary);" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                                <span class="text-[17px] text-gray-700">{{ $benefit }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <p class="text-[14px] text-gray-600 mb-6">Join leading companies using the #1 scheduling tool</p>
                        <div class="flex items-center gap-8 opacity-60">
                            @foreach(['Dropbox', 'Ancestry', 'Zendesk', "L'ORÉAL"] as $company)
                                <span class="text-[13px] font-semibold text-gray-600">{{ $company }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection