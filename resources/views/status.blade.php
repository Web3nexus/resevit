@extends('layouts.landing')

@section('title', 'System Status - ' . config('app.name'))

@section('content')
    <div class="bg-slate-50 min-h-screen py-12 md:py-20">
        <div class="max-w-5xl mx-auto px-4">
            <!-- Header -->
            <header class="mb-12">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 mb-2">Systems Status</h1>
                <p class="text-slate-500">Real-time health monitoring for all platform services.</p>
            </header>

            <!-- Services Grid -->
            <section class="mb-16">
                <h2 class="text-xl font-semibold mb-6 flex items-center text-slate-800">
                    Services
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($services as $service)
                        <div
                            class="bg-white p-6 rounded-xl border border-slate-200 flex justify-between items-start shadow-sm hover:shadow-md transition-all duration-200">
                            <div>
                                <h3 class="font-bold text-slate-800">{{ $service['name'] }}</h3>
                                <p class="text-[10px] text-slate-400 mt-1 uppercase font-semibold tracking-wider">
                                    {{ $service['description'] }}</p>
                            </div>
                            <div class="flex items-center">
                                <span
                                    class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Uptime Stats -->
            <section class="mb-16">
                <h2 class="text-xl font-semibold mb-6 text-slate-800">Uptime</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
                        <p class="text-[10px] text-slate-400 font-bold mb-3 uppercase tracking-widest">Current Status</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-bold text-emerald-600">{{ $uptimePercentage }}%</span>
                        </div>
                    </div>
                    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
                        <p class="text-[10px] text-slate-400 font-bold mb-3 uppercase tracking-widest">Monitored Services
                        </p>
                        <div class="text-4xl font-bold text-slate-800">{{ $monitoredServices }}</div>
                    </div>
                    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
                        <p class="text-[10px] text-slate-400 font-bold mb-3 uppercase tracking-widest">Services Online</p>
                        <div class="text-4xl font-bold text-slate-800">{{ $servicesOnline }}/{{ $monitoredServices }}</div>
                    </div>
                </div>
            </section>

            <!-- Monitored Endpoints -->
            <section>
                <h2 class="text-xl font-semibold mb-6 text-slate-800">Monitored Endpoints</h2>
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex flex-col md:flex-row items-center gap-4">
                            <span class="font-bold text-slate-700 tracking-tight">{{ config('app.url') }}</span>
                            <span
                                class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full uppercase tracking-wider">Operational</span>
                        </div>
                        <div class="flex items-center gap-6">
                            <span class="text-xs text-slate-400 font-medium whitespace-nowrap">Last checked:
                                {{ $lastChecked }}</span>
                            <span
                                class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection