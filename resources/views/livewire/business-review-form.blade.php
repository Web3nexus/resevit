<div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
    <h3 class="text-2xl font-bold text-brand-primary mb-6">Leave a Review</h3>

    @if($successMessage)
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-2xl mb-6 flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ $successMessage }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-4">Rating</label>
            <div class="flex items-center space-x-2">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button" wire:click="setRating({{ $i }})"
                        class="focus:outline-none transition-transform hover:scale-110">
                        <svg class="w-10 h-10 {{ $i <= $rating ? 'text-brand-accent' : 'text-slate-200' }} fill-current"
                            viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                    </button>
                @endfor
            </div>
            @error('rating') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="comment" class="block text-sm font-bold text-slate-700 mb-2">Your Experience</label>
            <textarea id="comment" wire:model.defer="comment" rows="4"
                class="block w-full rounded-2xl border-slate-200 shadow-sm focus:border-brand-accent focus:ring focus:ring-brand-accent/20 transition-all text-slate-600"
                placeholder="Share your thoughts about this business..."></textarea>
            @error('comment') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        @auth
            <button type="submit"
                class="w-full inline-flex justify-center items-center px-8 py-4 bg-brand-primary text-white font-bold rounded-2xl hover:bg-brand-secondary transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Post Review</span>
                <span wire:loading>Submitting...</span>
            </button>
        @else
            <div class="text-center bg-slate-50 p-6 rounded-2xl border border-dashed border-slate-200">
                <p class="text-slate-500 text-sm mb-4">You must be logged in to leave a review.</p>
                <a href="{{ route('login') }}"
                    class="text-brand-primary font-bold hover:text-brand-accent transition-colors">Log In or Register</a>
            </div>
        @endauth
    </form>
</div>