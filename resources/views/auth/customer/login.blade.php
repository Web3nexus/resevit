<x-auth-split-layout heading="Welcome back" subheading="Sign in to your account">
    <form method="POST" action="{{ route('customer.login') }}" class="grid gap-y-6">
        @csrf

        <div class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-950 dark:text-white">Email
                    address</label>
                <div class="mt-2">
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="block w-full rounded-lg border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-custom-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-custom-500">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password"
                    class="block text-sm font-medium leading-6 text-gray-950 dark:text-white">Password</label>
                <div class="mt-2">
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="block w-full rounded-lg border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-custom-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/10 dark:focus:ring-custom-500">
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-custom-600 focus:ring-custom-600 dark:border-white/10 dark:bg-white/5 dark:focus:ring-offset-gray-900">
                    <label for="remember" class="ml-3 block text-sm leading-6 text-gray-900 dark:text-gray-400">Remember
                        me</label>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="flex w-full justify-center rounded-lg bg-custom-600 px-3 py-1.5 text-sm font-bold leading-6 text-gold-500 shadow-sm hover:bg-custom-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-custom-600 text-[#C5A059]">
                    Sign in
                </button>
            </div>
        </div>
    </form>

    <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
        Not a member?
        <a href="{{ route('filament.dashboard.auth.register') }}"
            class="font-semibold text-custom-600 hover:text-custom-500 dark:text-custom-400">Register now</a>
    </div>
</x-auth-split-layout>