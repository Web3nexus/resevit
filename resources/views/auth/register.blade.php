<x-guest-layout>
    <div class="px-6 py-6 sm:px-8">
        <h2 class="mb-1 text-2xl font-bold text-gray-900">Create Account</h2>
        <p class="mb-6 text-sm text-gray-600">Register to get started</p>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                @foreach ($errors->all() as $error)
                    <p class="text-red-700 text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4" x-data="{ role: '{{ old('role', 'business_owner') }}' }">
            @csrf

            <!-- Role Selection -->
            <div class="border-b pb-4 mb-4">
                 <h3 class="text-sm font-semibold text-gray-900 mb-4">Account Type</h3>
                <div>
                    <x-input-label for="role">Register as</x-input-label>
                    <select id="role" name="role" x-model="role" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="business_owner">Business Owner</option>
                        <option value="investor">Investor</option>
                        <option value="customer">Customer</option>
                        <option value="staff">Staff</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" />
                </div>
                 <!-- Staff Warning Message -->
                <div x-show="role === 'staff'" style="display: none;" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-yellow-800 text-sm font-medium">Staff accounts cannot be created through this form. Please ask your employer to create an account for you.</p>
                </div>
            </div>

            <!-- Personal Information -->
            <fieldset :disabled="role === 'staff'" class="border-b pb-4 mb-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Personal Information</h3>

                <div>
                    <x-input-label for="name">Full Name</x-input-label>
                    <x-text-input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md disabled:bg-gray-100" />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email">Email Address</x-input-label>
                    <x-text-input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md disabled:bg-gray-100" />
                    <x-input-error :messages="$errors->get('email')" />
                </div>
            </fieldset>

            <!-- Business Information (Conditional) -->
            <div class="border-b pb-4 mb-4" x-show="role === 'business_owner'" style="display: none;">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Business Information</h3>
                <div>
                    <x-input-label for="restaurant_name">Restaurant Name</x-input-label>
                    <x-text-input id="restaurant_name" name="restaurant_name" type="text" :value="old('restaurant_name')" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" />
                    <x-input-error :messages="$errors->get('restaurant_name')" />
                </div>
            </div>


            <!-- Security -->
            <fieldset :disabled="role === 'staff'">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Security</h3>

                <div>
                    <x-input-label for="password">Password</x-input-label>
                    <x-text-input id="password" name="password" type="password" required autocomplete="new-password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md disabled:bg-gray-100" />
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation">Confirm Password</x-input-label>
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md disabled:bg-gray-100" />
                </div>
            </fieldset>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-500">Already registered? Login</a>
                <x-primary-button class="ms-4 px-6" :disabled="role === 'staff'">Register</x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
