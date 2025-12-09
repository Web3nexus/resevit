<x-guest-layout>
    <h1>Customer Login</h1>

    @if($errors->any())
        <div style="color:red">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('customer.login') }}">
        @csrf
        <div>
            <x-input-label for="email">Email</x-input-label>
            <x-text-input id="email" name="email" type="email" value="{{ old('email') }}" required />
        </div>

        <div>
            <x-input-label for="password">Password</x-input-label>
            <x-text-input id="password" name="password" type="password" required />
        </div>

        <div>
            <label><input type="checkbox" name="remember"> Remember me</label>
        </div>

        <div class="mt-4">
            <x-primary-button>Log in</x-primary-button>
        </div>
    </form>

    <p>Need an account? <a href="{{ route('customer.register') }}">Register</a></p>
</x-guest-layout>
