<x-filament-widgets::widget>
    <div x-data="{ activeTab: 'refer' }" class="space-y-6">
        {{-- Tabs Navigation --}}
        <div class="flex items-center gap-8 border-b border-gray-100 dark:border-gray-800 pb-px">
            <button @click="activeTab = 'refer'"
                :class="activeTab === 'refer' ? 'text-primary-600 dark:text-primary-400 border-primary-600 dark:border-primary-400' : 'text-gray-500 dark:text-gray-400 border-transparent hover:text-gray-700 dark:hover:text-gray-300'"
                class="pb-4 text-sm font-bold border-b-2 transition-all">
                Refer & earn
            </button>
            <button @click="activeTab = 'earnings'"
                :class="activeTab === 'earnings' ? 'text-primary-600 dark:text-primary-400 border-primary-600 dark:border-primary-400' : 'text-gray-500 dark:text-gray-400 border-transparent hover:text-gray-700 dark:hover:text-gray-300'"
                class="pb-4 text-sm font-bold border-b-2 transition-all">
                My earnings
            </button>
        </div>

        {{-- Tab Content: Refer & Earn --}}
        <div x-show="activeTab === 'refer'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
            {{-- Hostinger Banner --}}
            <div
                class="relative overflow-hidden rounded-2xl bg-[#110D2C] text-white p-10 group min-h-[200px] flex items-center shadow-2xl">
                <div class="absolute top-0 right-0 w-1/3 h-full bg-linear-to-l from-primary-500/10 to-transparent z-0">
                </div>
                <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-primary-600/20 rounded-full blur-3xl z-0"></div>

                <div class="relative z-10 w-full flex flex-col md:flex-row items-center justify-between gap-12">
                    <div class="flex-1 space-y-5 text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2">
                            <div class="bg-[#F6AD55] text-[#110D2C] rounded-md p-1">
                                <x-heroicon-m-ticket class="w-3 h-3" style="width: 12px; height: 12px;" />
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#F6AD55]">Limited time
                                offer</span>
                        </div>

                        <h2 class="text-3xl md:text-4xl font-black tracking-tight leading-tight">
                            Refer and earn up to <span class="text-primary-400">Unlimited Commissions</span>
                        </h2>

                        <div x-data="{ 
                            copied: false,
                            copy() {
                                window.navigator.clipboard.writeText('{{ $referral_url }}');
                                this.copied = true;
                                setTimeout(() => this.copied = false, 2000);
                            }
                        }" class="flex flex-col sm:flex-row items-stretch gap-3 max-w-2xl mx-auto md:mx-0">
                            <div class="relative flex-1 group/input">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <x-heroicon-m-link class="w-4 h-4 text-gray-400"
                                        style="width: 16px; height: 16px;" />
                                </div>
                                <input type="text" readonly value="{{ $referral_url }}"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl py-4 pl-11 pr-4 text-sm font-mono text-gray-200 focus:outline-hidden focus:ring-2 focus:ring-primary-400 focus:border-transparent transition-all"
                                    @click="$el.select()">
                            </div>

                            <button @click="copy()"
                                class="bg-primary-600 hover:bg-primary-500 text-white font-bold py-4 px-10 rounded-xl transition-all active:scale-[0.98] shadow-lg shadow-primary-900/40 min-w-[160px]">
                                <span x-show="!copied">Copy link</span>
                                <span x-show="copied" x-cloak class="flex items-center gap-2">
                                    <x-heroicon-m-check class="w-5 h-5 text-white" style="width: 20px; height: 20px;" />
                                    <span>Copied!</span>
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="hidden lg:block relative shrink-0">
                        <div class="relative z-10 w-32 h-32 flex items-center justify-center">
                            <div class="absolute inset-0 bg-primary-500/20 rounded-full blur-2xl animate-pulse"></div>
                            <div
                                class="relative transform rotate-12 transition-transform group-hover:rotate-0 duration-700">
                                <div class="text-8xl font-black text-white/10 select-none">%</div>
                                <div
                                    class="absolute inset-0 text-8xl font-black text-transparent bg-clip-text bg-linear-to-br from-primary-400 to-primary-600 drop-shadow-[0_0_20px_rgba(var(--primary-400),0.5)]">
                                    %</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab Content: My Earnings --}}
        <div x-show="activeTab === 'earnings'" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Progress Dashboard --}}
                <div
                    class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 shadow-sm">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-12">
                        <div class="space-y-10 flex-1 w-full">
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <h3
                                        class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">
                                        Referrals commission payout</h3>
                                    <x-heroicon-m-information-circle class="w-4 h-4 text-gray-300"
                                        style="width: 16px; height: 16px;" />
                                </div>
                                <div class="text-5xl font-black text-[#6366F1] dark:text-[#818CF8] tracking-tighter">
                                    US$ {{ number_format($availableBalance, 2) }}
                                </div>
                            </div>

                            <div class="relative pt-10 pb-6">
                                <div class="h-3 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary-600 dark:bg-primary-500 transition-all duration-1000 shadow-[0_0_15px_rgba(var(--primary-600),0.3)]"
                                        style="width: {{ $progressPercentage }}%"></div>
                                </div>

                                <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
                                    @foreach($milestones as $milestone)
                                        @php
                                            $percent = ($milestone['amount'] / end($milestones)['amount']) * 100;
                                            $isReached = $availableBalance >= $milestone['amount'];
                                        @endphp
                                        <div class="absolute top-9.5 transform -translate-x-1/2"
                                            style="left: {{ $percent }}%">
                                            <div
                                                class="w-4 h-4 rounded-full border-4 border-white dark:border-gray-950 {{ $isReached ? 'bg-primary-600 dark:bg-primary-500' : 'bg-gray-200 dark:bg-gray-700' }} shadow-sm">
                                            </div>
                                            <div class="mt-5 text-center">
                                                <div class="text-[11px] font-black text-gray-900 dark:text-white">US$
                                                    {{ $milestone['amount'] }}</div>
                                                <div
                                                    class="text-[9px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-tighter">
                                                    {{ $milestone['label'] }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div
                            class="w-full md:w-72 bg-gray-50 dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/10">
                            <div class="flex items-center gap-4 mb-5">
                                <div class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                                    <x-heroicon-m-wallet class="w-6 h-6 text-primary-600 dark:text-primary-400"
                                        style="width: 24px; height: 24px;" />
                                </div>
                                <h4
                                    class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-widest leading-tight">
                                    Payout<br>Method</h4>
                            </div>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-8 leading-relaxed font-medium">
                                We'll pay you automatically to your chosen method once the minimum is reached.
                            </p>
                            <x-filament::button tag="a" href="{{ $bankUrl }}"
                                color="{{ $hasBankSetup ? 'gray' : 'primary' }}" size="lg"
                                class="w-full shadow-lg transition-transform active:scale-95">
                                {{ $hasBankSetup ? 'Manage Method' : 'Setup Payout' }}
                            </x-filament::button>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div
                        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm flex items-center gap-5 group hover:border-primary-500/30 transition-colors">
                        <div
                            class="p-4 bg-primary-50 dark:bg-primary-500/10 rounded-2xl transition-transform group-hover:scale-110">
                            <x-heroicon-m-gift class="w-7 h-7 text-primary-600 dark:text-primary-400"
                                style="width: 28px; height: 28px;" />
                        </div>
                        <div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">US$
                                {{ number_format($totalPaidOut, 2) }}</div>
                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total paid out
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm flex items-center gap-5 group hover:border-emerald-500/30 transition-colors">
                        <div
                            class="p-4 bg-emerald-50 dark:bg-emerald-500/10 rounded-2xl transition-transform group-hover:scale-110">
                            <x-heroicon-m-users class="w-7 h-7 text-emerald-600 dark:text-emerald-400"
                                style="width: 28px; height: 28px;" />
                        </div>
                        <div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">
                                {{ $totalReferrals }}</div>
                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Successful
                                referrals</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Invites Table (Mini version) --}}
            <div
                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
                <div class="px-8 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Recent successful invites</h3>
                    <x-filament::link href="#" size="sm">View all referrals</x-filament::link>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr
                                class="text-[10px] uppercase tracking-widest text-gray-400 border-b border-gray-50 dark:border-gray-800/50">
                                <th class="px-8 py-4 font-bold">User</th>
                                <th class="px-8 py-4 font-bold">Status</th>
                                <th class="px-8 py-4 font-bold">Joined</th>
                                <th class="px-8 py-4 font-bold text-right">Earnings</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                            @forelse($recentReferrals as $referral)
                                <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors">
                                    <td class="px-8 py-4 font-bold text-gray-700 dark:text-gray-300">
                                        {{ $referral->referee->email }}
                                    </td>
                                    <td class="px-8 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 uppercase tracking-wider border border-emerald-100 dark:border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 text-gray-400 text-xs">
                                        {{ $referral->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-8 py-4 text-right font-mono font-bold text-gray-900 dark:text-white">
                                        +US$ 5.00
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-gray-400 italic">
                                        No referrals found. Start sharing your link to earn!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>