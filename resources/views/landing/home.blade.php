@extends('layouts.landing')

@section('content')
    {{-- Static Hero Section --}}
    <section class="relative bg-brand-primary text-white overflow-hidden py-24 sm:py-32">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-accent rounded-full blur-[120px] -mr-64 -mt-64"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-brand-secondary rounded-full blur-[100px] -ml-48 -mb-48"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                     <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-widest uppercase bg-brand-accent/20 text-brand-accent mb-6 border border-brand-accent/30">
                        THE FUTURE OF DINING
                    </span>
                    <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight leading-[1.1] mb-8">
                        Maximize Your Restaurant’s <span class="text-brand-accent">Potential</span>
                    </h1>
                    <p class="text-xl text-slate-300 mb-10 leading-relaxed max-w-xl">
                        Streamline reservations, optimize staff schedules, and delight customers with the world's most advanced restaurant management platform.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-brand-accent text-brand-primary font-bold rounded-xl hover:scale-105 transition-transform shadow-xl shadow-brand-accent/20">
                            Get Started Free
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                        <a href="#" class="inline-flex items-center justify-center px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition-all border border-white/10">
                            Watch Demo
                        </a>
                    </div>
                     <div class="mt-12 flex items-center space-x-6 grayscale opacity-60">
                        <span class="text-sm font-medium text-slate-500 uppercase tracking-widest">Trusted By</span>
                         <div class="flex space-x-8">
                            <div class="w-8 h-8 rounded-full bg-white/20"></div>
                            <div class="w-8 h-8 rounded-full bg-white/20"></div>
                            <div class="w-8 h-8 rounded-full bg-white/20"></div>
                        </div>
                    </div>
                </div>

                <div class="relative group">
                     <div class="absolute inset-0 bg-brand-accent/20 rounded-3xl blur-3xl rotate-3 transform group-hover:rotate-6 transition-transform duration-500"></div>
                    <div class="relative bg-brand-primary/50 backdrop-blur-sm border border-white/10 p-4 rounded-3xl shadow-2xl overflow-hidden">
                        <div class="aspect-video bg-gradient-to-br from-brand-primary to-slate-900 rounded-2xl flex items-center justify-center border border-white/5">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-brand-accent/20 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-brand-accent/30">
                                    <svg class="w-8 h-8 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                </div>
                                <span class="text-slate-400 font-medium">Interactive Platform Preview</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Static Features Section --}}
    <section class="py-24 bg-brand-offwhite">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <span class="text-brand-accent font-bold tracking-widest uppercase text-sm mb-4 block">CORE CAPABILITIES</span>
                <h2 class="text-4xl font-extrabold text-brand-primary mb-6 tracking-tight">Powerful Features to Control Your Success</h2>
                <p class="text-lg text-slate-600 leading-relaxed">Everything you need to run your restaurant, all in one intuitive platform.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-16 h-16 bg-brand-primary rounded-2xl flex items-center justify-center mb-8 group-hover:bg-brand-accent transition-colors duration-300">
                        <svg class="w-8 h-8 text-white group-hover:text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-brand-primary mb-4">Smart Reservations</h3>
                    <p class="text-slate-600 leading-relaxed mb-6">Automate bookings, reduce no-shows, and maximize table turnover with AI-driven scheduling.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                    <div class="w-16 h-16 bg-brand-primary rounded-2xl flex items-center justify-center mb-8 group-hover:bg-brand-accent transition-colors duration-300">
                         <svg class="w-8 h-8 text-white group-hover:text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-brand-primary mb-4">Staff Management</h3>
                    <p class="text-slate-600 leading-relaxed mb-6">Effortlessly manage shifts, payroll, and performance with integrated team tools.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                     <div class="w-16 h-16 bg-brand-primary rounded-2xl flex items-center justify-center mb-8 group-hover:bg-brand-accent transition-colors duration-300">
                        <svg class="w-8 h-8 text-white group-hover:text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-brand-primary mb-4">Analytics & Growth</h3>
                    <p class="text-slate-600 leading-relaxed mb-6">Real-time insights into sales, customer preferences, and inventory to drive profitability.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
