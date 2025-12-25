<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Investor Registration - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>

<body class="antialiased">
    <x-auth-split-layout heading="Become an Investor" subheading="Join our global investment community">

        <form action="{{ route('investor.register') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <div class="mt-1">
                        <input id="name" name="name" type="text" autocomplete="name" required
                            class="block w-full appearance-none rounded-lg border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-navy-900 focus:outline-none focus:ring-navy-900 sm:text-sm">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="block w-full appearance-none rounded-lg border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-navy-900 focus:outline-none focus:ring-navy-900 sm:text-sm">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                            class="block w-full appearance-none rounded-lg border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-navy-900 focus:outline-none focus:ring-navy-900 sm:text-sm">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                        Password</label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            autocomplete="new-password" required
                            class="block w-full appearance-none rounded-lg border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-navy-900 focus:outline-none focus:ring-navy-900 sm:text-sm">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="flex w-full justify-center rounded-lg border border-transparent bg-navy-900 py-2.5 px-4 text-sm font-semibold text-white shadow-sm hover:bg-navy-800 focus:outline-none focus:ring-2 focus:ring-navy-900 focus:ring-offset-2 transition-colors">
                    Register
                </button>
            </div>

            <p class="mt-2 text-center text-sm text-gray-600">
                Already have an investor account?
                <a href="{{ route('investor.login') }}" class="font-medium text-navy-900 hover:text-navy-700">
                    Sign in here
                </a>
            </p>
        </form>
    </x-auth-split-layout>
</body>

</html>