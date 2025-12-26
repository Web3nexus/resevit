<div>
    @if($success)
        <div class="text-center py-12 animate-bounce-in">
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-4xl"></i>
            </div>
            <h3 class="text-3xl font-black text-brand-primary mb-2 uppercase">Thank You!</h3>
            <p class="text-slate-500 mb-8 max-w-xs mx-auto">Your reservation request has been submitted successfully.</p>
            <x-filament::button wire:click="$set('success', false)" color="gray">
                Make Another Reservation
            </x-filament::button>
        </div>
    @else
        <form wire:submit.prevent="submit" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-500">Your Full Name</label>
                    <input type="text" wire:model="name" placeholder="John Doe"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-brand-accent transition-all">
                    @error('name') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-500">Party Size</label>
                    <div class="relative">
                        <select wire:model="party_size"
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-brand-accent appearance-none transition-all">
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }} People</option>
                            @endfor
                            <option value="11">10+ People</option>
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-500">Email Address</label>
                    <input type="email" wire:model="email" placeholder="john@example.com"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-brand-accent transition-all">
                    @error('email') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-500">Phone Number</label>
                    <input type="tel" wire:model="phone" placeholder="+1 (555) 000-0000"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-brand-accent transition-all">
                    @error('phone') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-500">Booking Date</label>
                    <input type="date" wire:model="date"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-brand-accent transition-all">
                    @error('date') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-500">Prefered Time</label>
                    <input type="time" wire:model="time"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-brand-accent transition-all">
                    @error('time') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-black uppercase tracking-widest text-slate-500">Special Requests
                    (Optional)</label>
                <textarea wire:model="special_requests" rows="3"
                    placeholder="Any allergies or celebrations we should know about?"
                    class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-brand-accent transition-all"></textarea>
                @error('special_requests') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full py-5 bg-brand-primary text-white font-black rounded-2xl hover:bg-brand-secondary transition-all shadow-xl hover:shadow-2xl hover:scale-[1.01] active:scale-100 flex items-center justify-center space-x-2">
                    <span wire:loading.remove>Confirm Reservation</span>
                    <span wire:loading>Processing...</span>
                    <i wire:loading.remove class="fas fa-arrow-right"></i>
                </button>
            </div>

            <p class="text-center text-[10px] text-slate-400 uppercase tracking-widest mt-6">
                By booking you agree to our terms and cancellation policy.
            </p>
        </form>
    @endif
</div>