<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Investor Login - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>

<body class="antialiased">
    <x-auth-split-layout heading="Investor Portal" subheading="Sign in to manage your portfolio">

        <form action="{{ route('investor.login') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-4">
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
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full appearance-none rounded-lg border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-navy-900 focus:outline-none focus:ring-navy-900 sm:text-sm">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-navy-900 focus:ring-navy-900">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-navy-900 hover:text-navy-700">Forgot password?</a>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="flex w-full justify-center rounded-lg border border-transparent bg-navy-900 py-2.5 px-4 text-sm font-semibold text-white shadow-sm hover:bg-navy-800 focus:outline-none focus:ring-2 focus:ring-navy-900 focus:ring-offset-2 transition-colors">
                    Sign in
                </button>
            </div>

            <p class="mt-2 text-center text-sm text-gray-600">
                Want to join as an investor?
                <a href="{{ route('investor.register') }}" class="font-medium text-navy-900 hover:text-navy-700">
                    Register now
                </a>
            </p>
        </form>
    </x-auth-split-layout>
</body>

</html>