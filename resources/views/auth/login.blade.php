<x-guest-layout>
  <div class="px-6 py-6 sm:px-8">
    <h2 class="mb-1 text-2xl font-bold text-gray-900">Welcome Back</h2>
    <p class="mb-6 text-sm text-gray-600">Sign in to your account</p>

    @if($errors->any())
      <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        @foreach ($errors->all() as $error)
          <p class="text-red-700 text-sm">{{ $error }}</p>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
      @csrf

      <div>
        <x-input-label for="email">Email Address</x-input-label>
        <x-text-input id="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" />
        <x-input-error :messages="$errors->get('email')" />
      </div>

      <div>
        <x-input-label for="password">Password</x-input-label>
        <x-text-input id="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md"
                type="password"
                name="password"
                required autocomplete="current-password" />
        <x-input-error :messages="$errors->get('password')" />
      </div>

      <div class="flex items-center justify-between">
        <label for="remember_me" class="inline-flex items-center">
          <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
          <span class="ms-2 text-sm text-gray-600">Remember me</span>
        </label>
        
        @if (Route::has('password.request'))
          <a class="text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}">
            Forgot password?
          </a>
        @endif
      </div>

      <div class="mt-6">
        <x-primary-button class="w-full justify-center py-2">
          Sign In
        </x-primary-button>
      </div>
    </form>

    <!-- Sign Up Link -->
    <div class="mt-4 text-center border-t pt-4">
      <p class="text-sm text-gray-600">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500 font-semibold">
          Create one
        </a>
      </p>
    </div>

    <!-- OAuth Divider -->
    <div class="mt-6 relative">
      <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-gray-300"></div>
      </div>
      <div class="relative flex justify-center text-sm">
        <span class="px-2 bg-white text-gray-500">Or continue with</span>
      </div>
    </div>

    <!-- OAuth Buttons -->
    <div class="mt-6 grid grid-cols-3 gap-3">
      <a href="{{ route('oauth.redirect', 'google') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032 c0-3.331,2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.461,2.268,15.365,1,12.545,1 C6.477,1,1.54,5.952,1.54,12s4.938,11,11.005,11c6.067,0,11.067-4.941,11.067-11c0-0.708-0.07-1.413-0.194-2.103h-10.894V10.239z"/></svg>
      </a>

      <a href="{{ route('oauth.redirect', 'facebook') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
      </a>

      <a href="{{ route('oauth.redirect', 'apple') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.05 20.28c-.98.95-2.05.88-3.08.4-1.09-.5-2.08-.48-3.24 0-1.44.62-2.2.44-3.06-.4C2.79 15.25 3.51 7.59 9.05 7.31c1.35.08 2.29.74 3.08.8.905-.08 1.84-.74 3.02-.8 4.42.206 7.26 4.09 7.26 4.09-.4.55-2.67 1.675-4.36 2.885.96.84 1.61 2.08 1.42 3.72-1.72 1.3-2.82 1.325-4.42 1.325-1.12 0-1.8-.15-2.66-.4zM12.03 5.29c-.74-1.46-1.61-2.38-3.14-2.25-1.53.13-2.82 1.17-3.22 2.25-.06.3 0 .61.1.91.1-.02.21-.03.31-.02 1.33.1 2.41.78 3.22 1.62.93-.99 2.08-1.58 3.34-1.62.1 0 .2.01.31.02-.1-.29-.16-.6-.1-.91.15-.3.36-.61.58-.93.22-.31.36-.62.36-.98z"/></svg>
      </a>
    </div>
  </div>
</x-guest-layout>
