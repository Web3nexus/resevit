<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <x-filament-schemas::form wire:submit.prevent="updateProfile" class="space-y-6">
                {{ $this->profileForm }}

                <div class="flex justify-end">
                    <x-filament::button type="submit" size="lg" class="px-8 shadow-md">
                        Save Changes
                    </x-filament::button>
                </div>
            </x-filament-schemas::form>
        </div>

        <!-- Promotion Section -->
        <div class="space-y-6">
            <div
                class="bg-brand-primary text-white rounded-3xl p-8 shadow-xl border border-white/5 overflow-hidden relative">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-brand-accent p-2 rounded-lg">
                                <x-heroicon-o-rocket-launch class="w-6 h-6 text-brand-primary" />
                            </div>
                            <h3 class="text-xl font-bold">Boost Visibility</h3>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-tighter">Your Balance</p>
                            <p class="text-lg font-black text-brand-accent">
                                ${{ number_format(auth()->user()->wallet_balance, 2) }}</p>
                        </div>
                    </div>

                    <p class="text-slate-400 text-sm mb-8 leading-relaxed">
                        Appear at the top of search results and highlight your business with a <span
                            class="text-brand-accent font-black">Featured</span> badge.
                    </p>

                    @php
                        $tenant = tenant();
                        $isPromoted = $tenant->is_sponsored && $tenant->promotion_expires_at && \Illuminate\Support\Carbon::parse($tenant->promotion_expires_at)->isFuture();
                    @endphp

                    @if ($isPromoted)
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 mb-8 border border-white/10">
                            <div class="flex items-center gap-3 text-brand-accent mb-1">
                                <x-heroicon-s-check-circle class="w-5 h-5" />
                                <span class="text-xs font-black uppercase tracking-widest">Active Promotion</span>
                            </div>
                            <p class="text-slate-300 text-[10px]">Expires:
                                {{ \Illuminate\Support\Carbon::parse($tenant->promotion_expires_at)->format('M d, Y') }}
                            </p>
                        </div>
                    @endif

                    <div class="space-y-3">
                        <x-filament::button wire:click="buyPromotion(7)" color="success"
                            class="w-full bg-brand-accent text-brand-primary font-black border-none hover:bg-white transition-all transform hover:scale-105">
                            7 Days - ${{ number_format($this->prices[7], 2) }}
                        </x-filament::button>

                        <x-filament::button wire:click="buyPromotion(30)" outlined
                            class="w-full text-white border-white/30 hover:bg-white/10 transition-all font-bold">
                            30 Days - ${{ number_format($this->prices[30], 2) }}
                        </x-filament::button>
                    </div>

                    <p class="text-[10px] text-slate-500 mt-6 text-center italic">
                        * Sponsored businesses appear first in their categories.
                    </p>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <h4 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-6 px-2">Preview</h4>
                <div class="border border-slate-100 rounded-2xl overflow-hidden bg-slate-50/50">
                    <div class="h-32 bg-slate-200">
                        @if($profileData['cover_image'])
                            <img src="{{ Storage::url($profileData['cover_image']) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300 text-3xl font-black">
                                {{ substr(tenant()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h5 class="font-bold text-brand-primary truncate">{{ tenant()->name }}</h5>
                        <p class="text-[10px] text-slate-400 line-clamp-2 mt-1">
                            {{ $profileData['description'] ?: 'No description provided yet.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>