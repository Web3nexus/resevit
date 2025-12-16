<div class="space-y-6">
    {{-- Divider --}}
    <div class="relative py-4">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="bg-white px-2 text-gray-500">
                Continue with:
            </span>
        </div>
    </div>

    {{-- Social Login --}}
    <div class="flex gap-3">
        {{-- Google --}}
        <a
            href="{{ route('auth.google') }}"
            aria-label="Continue with Google"
            class="flex justify-center items-center h-12 w-full rounded-xl border border-gray-200 bg-white
           shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <svg class="size-5" viewBox="0 0 24 24" fill="none">
                <path d="M23.766 12.2764C23.766 11.4607 23.6999 10.6406 23.5588 9.83807H12.24V14.4591H18.7217C18.4528 15.9494 17.5885 17.2678 16.323 18.1056V21.1039H20.19C22.4608 19.0139 23.766 15.9274 23.766 12.2764Z" fill="#4285F4" />
                <path d="M12.2401 24.0008C15.4766 24.0008 18.2059 22.9382 20.1945 21.1039L16.3275 18.1055C15.2517 18.8375 13.8627 19.252 12.2445 19.252C9.11388 19.252 6.45946 17.1399 5.50705 14.3003H1.5166V17.3912C3.55371 21.4434 7.7029 24.0008 12.2401 24.0008Z" fill="#34A853" />
                <path d="M5.50253 14.3003C5.00236 12.8099 5.00236 11.1961 5.50253 9.70575V6.61481H1.51649C-0.18551 10.0056 -0.18551 14.0004 1.51649 17.3912L5.50253 14.3003Z" fill="#FBBC05" />
                <path d="M12.2401 4.74966C13.9509 4.7232 15.6044 5.36697 16.8434 6.54867L20.2695 3.12262C18.1001 1.0855 15.2208 -0.034466 12.2401 0.000808666C7.7029 0.000808666 3.55371 2.55822 1.5166 6.61481L5.50264 9.70575C6.45064 6.86173 9.10947 4.74966 12.2401 4.74966Z" fill="#EA4335" />
            </svg>
        </a>

        {{-- Apple --}}
        <a
            href="{{ route('auth.apple') }}"
            aria-label="Continue with Apple"
            class="flex justify-center items-center h-12 w-full rounded-xl border border-gray-200 bg-white
           shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <svg class="size-5 text-gray-900 dark:text-white" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.05 20.28C15.82 22.04 14.16 22 13.06 21.98C11.92 21.96 10.82 21.36 9.77 21.36C8.7 21.36 7.42 22 6.44 22C4.12 21.96 1.14 17.58 3.1 13.56C4.06 11.66 5.86 10.5 7.6 10.46C9.06 10.42 10 11.24 10.94 11.24C11.84 11.24 13.14 10.22 14.7 10.3C15.34 10.32 17.1 10.5 18.24 12.16C18.14 12.22 16.2 13.34 16.22 15.54C16.24 17.42 17.88 18.5 17.96 18.54C17.9 18.84 17.7 19.5 17.05 20.28Z" />
            </svg>
        </a>
    </div>
</div>