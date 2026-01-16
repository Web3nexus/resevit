<x-auth-split-layout :heading="$this->getHeading()" :subheading="$this->getSubheading()">
    <x-filament-schemas::form wire:submit="authenticate" id="authenticate" class="grid gap-y-8 w-full">
        {{ $this->form }}
        <x-filament::button type="submit" form="authenticate" class="w-full">
            Sign in
        </x-filament::button>
        <div class="relative py-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="bg-white px-2 text-gray-500">Or continue with</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('auth.google') }}"
                class="flex w-full items-center justify-center gap-3 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M23.766 12.2764C23.766 11.4607 23.6999 10.6406 23.5588 9.83807H12.24V14.4591H18.7217C18.4528 15.9494 17.5885 17.2678 16.323 18.1056V21.1039H20.19C22.4608 19.0139 23.766 15.9274 23.766 12.2764Z"
                        fill="#4285F4" />
                    <path
                        d="M12.2401 24.0008C15.4766 24.0008 18.2059 22.9382 20.1945 21.1039L16.3275 18.1055C15.2517 18.8375 13.8627 19.252 12.2445 19.252C9.11388 19.252 6.45946 17.1399 5.50705 14.3003H1.5166V17.3912C3.55371 21.4434 7.7029 24.0008 12.2401 24.0008Z"
                        fill="#34A853" />
                    <path
                        d="M5.50253 14.3003C5.00236 12.8099 5.00236 11.1961 5.50253 9.70575V6.61481H1.51649C-0.18551 10.0056 -0.18551 14.0004 1.51649 17.3912L5.50253 14.3003Z"
                        fill="#FBBC05" />
                    <path
                        d="M12.2401 4.74966C13.9509 4.7232 15.6044 5.36697 16.8434 6.54867L20.2695 3.12262C18.1001 1.0855 15.2208 -0.034466 12.2401 0.000808666C7.7029 0.000808666 3.55371 2.55822 1.5166 6.61481L5.50264 9.70575C6.45064 6.86173 9.10947 4.74966 12.2401 4.74966Z"
                        fill="#EA4335" />
                </svg>
                <span>Google</span>
            </a>

            <a href="{{ route('auth.apple') }}"
                class="flex w-full items-center justify-center gap-3 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M17.05 20.28C15.82 22.04 14.16 22 13.06 21.98C11.92 21.96 10.82 21.36 9.77 21.36C8.7 21.36 7.42 22 6.44 22C4.12 21.96 1.14 17.58 3.1 13.56C4.06 11.66 5.86 10.5 7.6 10.46C9.06 10.42 10 11.24 10.94 11.24C11.84 11.24 13.14 10.22 14.7 10.3C15.34 10.32 17.1 10.5 18.24 12.16C18.14 12.22 16.2 13.34 16.22 15.54C16.24 17.42 17.88 18.5 17.96 18.54C17.9 18.84 17.7 19.5 17.05 20.28ZM12.04 7.22C12.56 6.54 12.92 5.58 12.82 4.62C11.96 4.72 10.96 5.22 10.34 5.92C9.78 6.56 9.38 7.54 9.5 8.44C10.42 8.52 11.5 7.9 12.04 7.22Z" />
                </svg>
                <span>Apple</span>
            </a>
        </div>
    </x-filament-schemas::form>
    <div class="mt-6 flex items-center justify-between text-sm">
        @if (filament()->hasRegistration())
            <div class="text-left">
                <p>Dont have an Account?
                    <a href="{{ filament()->getRegistrationUrl() }}"
                        class="font-medium text-navy-900 hover:text-gold-500 hover:underline">
                        Create an account
                    </a>

                </p>

            </div>
        @endif
        @if (filament()->hasPasswordReset())
            <div class="text-right">
                <a href="{{ filament()->getRequestPasswordResetUrl() }}"
                    class="font-medium text-navy-900 hover:text-gold-500 hover:underline">
                    Forgot password?
                </a>
            </div>
        @endif
    </div>
</x-auth-split-layout>