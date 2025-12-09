<x-guest-layout>
    <h1>Investor Registration</h1>

    <form method="POST" action="{{ route('investor.register') }}">
        @csrf

        <div>
            <x-input-label for="name">Name</x-input-label>
            <x-text-input id="name" name="name" type="text" value="{{ old('name') }}" required />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email">Email</x-input-label>
            <x-text-input id="email" name="email" type="email" value="{{ old('email') }}" required />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password">Password</x-input-label>
            <x-text-input id="password" name="password" type="password" required />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation">Confirm Password</x-input-label>
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" required />
        </div>

        <div class="mt-4">
            <x-primary-button>Register as Investor</x-primary-button>
        </div>
    </form>

    <p>Already have an account? <a href="{{ route('investor.login') }}">Log in</a></p>
</x-guest-layout>
