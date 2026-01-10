<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-gray-100">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-brand-primary/10">
                <svg class="h-6 w-6 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 10-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Two-Factor Authentication
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Please enter the 6-digit verification code sent to your email.
            </p>
        </div>

        <form wire:submit.prevent="verify" class="mt-8 space-y-6">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <input wire:model="code" type="text" maxlength="6" required
                        class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-brand-primary focus:border-brand-primary focus:z-10 sm:text-lg text-center tracking-[1em]"
                        placeholder="000000">
                </div>
            </div>

            @if($error)
                <div class="text-red-600 text-sm text-center">
                    {{ $error }}
                </div>
            @endif

            @if (session()->has('message'))
                <div class="text-green-600 text-sm text-center">
                    {{ session('message') }}
                </div>
            @endif

            <div class="flex flex-col space-y-4">
                <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-all duration-200">
                    Verify Code
                </button>

                <button type="button" wire:click="resend"
                    class="text-sm text-brand-primary hover:text-brand-primary/80 font-medium text-center transition-colors">
                    Didn't receive a code? Resend
                </button>
            </div>
        </form>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                Back to Login
            </a>
        </div>
    </div>
</div>