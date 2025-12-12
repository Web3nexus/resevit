<div class="flex min-h-screen bg-white">
    <!-- Left Section: Form -->
    <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white z-10 w-full lg:w-[45%]">
        <div class="mx-auto w-full max-w-md">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-navy font-sans">
                    Welcome back
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Please enter your details.
                    <a href="/register" class="font-medium text-blue-600 hover:text-blue-500">
                        Don't have an account?
                    </a>
                </p>
            </div>

            <div class="mt-8">
                <!-- Social Logins Placeholder -->
            <div class="grid grid-cols-2 gap-3 mb-6">
                    <div>
                        <form action="{{ route('auth.google') }}" method="GET">
                            <button type="submit" class="inline-flex w-full justify-center items-center rounded-lg border border-gray-300 bg-white py-2.5 px-4 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                                <i class="fab fa-google text-lg mr-2" style="color: #4285F4;"></i>
                                Google
                            </button>
                        </form>
                    </div>
                    <div>
                        <form action="{{ route('auth.apple') }}" method="GET">
                            <button type="submit" class="inline-flex w-full justify-center items-center rounded-lg border border-gray-300 bg-white py-2.5 px-4 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                                <i class="fab fa-apple text-lg mr-2 text-black"></i>
                                Apple
                            </button>
                        </form>
                    </div>
                </div>

                <div class="relative mb-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="bg-white px-2 text-gray-500">Or continue with email</span>
                    </div>
                </div>

                <form wire:submit="authenticate" class="space-y-6">
                    {{ $this->form }}

                    <div>
                         <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4rounded border-gray-300 text-navy focus:ring-gold">
                                <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                            </div>

                            <div class="text-sm">
                                <a href="#" class="font-medium text-navy hover:text-gold">Forgot your password?</a>
                            </div>
                        </div>

                        <button type="submit"
                            class="flex w-full justify-center rounded-xl bg-navy py-3 px-4 text-sm font-bold text-white shadow-lg shadow-navy/20 hover:bg-opacity-90 hover:scale-[1.01] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2">
                            Sign in
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Section: Visual -->
    <div class="relative hidden w-0 flex-1 lg:block bg-navy overflow-hidden">
        <!-- Abstract gradient background -->
        <div class="absolute inset-0 bg-gradient-to-br from-navy via-[#0f1b3d] to-[#0B132B]"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://grainy-gradients.vercel.app/noise.svg');"></div>
        
        <!-- Gold Accent Circle -->
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-gold rounded-full opacity-5 blur-[100px]"></div>

        <div class="flex h-full flex-col justify-center px-16 text-white relative z-10">
            <h3 class="text-4xl font-bold font-sans tracking-tight mb-6 leading-tight">
                "Resevit transformed how <br/> we manage bookings."
            </h3>
            <div class="flex items-center space-x-4">
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-gold to-yellow-600 flex items-center justify-center text-navy font-bold text-lg">
                    SM
                </div>
                <div>
                    <p class="text-base font-medium text-white">Sarah Miller</p>
                    <p class="text-sm text-gray-400">Manager, The Blue Fork</p>
                </div>
            </div>
            
            <div class="mt-12 flex space-x-2">
                <div class="w-4 h-1 bg-white/20 rounded"></div>
                <div class="w-16 h-1 bg-gold rounded"></div>
                <div class="w-4 h-1 bg-white/20 rounded"></div>
            </div>
        </div>
    </div>
</div>
