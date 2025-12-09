<x-guest-layout>
    <div class="px-6 py-6 sm:px-8">
        <h2 class="mb-1 text-2xl font-bold text-gray-900">Create Account</h2>
        <p class="mb-6 text-sm text-gray-600">Register your business to get started</p>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                @foreach ($errors->all() as $error)
                    <p class="text-red-700 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Personal Information -->
            <div class="border-b pb-4 mb-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Personal Information</h3>
                
                <div>
                    <x-input-label for="name">Full Name</x-input-label>
                    <x-text-input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email">Email Address</x-input-label>
                    <x-text-input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="phone">Phone Number</x-input-label>
                    <x-text-input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
                    <x-input-error :messages="$errors->get('phone')" />
                </div>
            </div>

            <!-- Business Information -->
            <div class="border-b pb-4 mb-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Business Information</h3>
                
                <div>
                    <x-input-label for="business_name">Business Name</x-input-label>
                    <x-text-input id="business_name" name="business_name" type="text" value="{{ old('business_name') }}" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
                    <x-input-error :messages="$errors->get('business_name')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="business_slug">Business Slug (URL-friendly name)</x-input-label>
                    <x-text-input id="business_slug" name="business_slug" type="text" value="{{ old('business_slug') }}" required placeholder="e.g., my-restaurant" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
                    <x-input-error :messages="$errors->get('business_slug')" />
                    <p class="mt-1 text-xs text-gray-500">Use lowercase letters, numbers, and hyphens only</p>
                </div>

                <div class="mt-4">
                    <x-input-label for="domain">Domain</x-input-label>
                    <x-text-input id="domain" name="domain" type="text" value="{{ old('domain') }}" required placeholder="e.g., mybusiness.local" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
                    <x-input-error :messages="$errors->get('domain')" />
                </div>
            </div>

            <!-- Security -->
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Security</h3>
                
                <div>
                    <x-input-label for="password">Password</x-input-label>
                    <x-text-input id="password" name="password" type="password" required autocomplete="new-password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation">Confirm Password</x-input-label>
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-500">Already registered? Login</a>
                <x-primary-button class="ms-4 px-6">Register</x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
