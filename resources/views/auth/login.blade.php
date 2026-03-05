<x-guest-layout>
    <div class="flex min-h-screen flex-col items-center justify-center bg-black px-4 py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        
        <!-- Background decoration -->
        <div class="absolute top-[-20%] right-[-10%] h-[500px] w-[500px] rounded-full bg-[#9BBE4A]/10 blur-[100px]"></div>
        <div class="absolute bottom-[-20%] left-[-10%] h-[500px] w-[500px] rounded-full bg-[#9BBE4A]/5 blur-[100px]"></div>

        <div class="w-full max-w-md space-y-8 z-10 opacity-0 animate-fade-in-up">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-[#9BBE4A] sm:text-5xl opacity-0 animate-fade-in-scale">
                    StockSutra
                </h1>
                <p class="mt-2 text-sm text-zinc-400 opacity-0 animate-fade-in">
                    Manage your inventory with precision
                </p>
            </div>

            <div class="bg-[#111111] py-8 px-4 shadow rounded-xl sm:px-10 border border-[#333333] opacity-0 animate-fade-in-up-delayed">
                
                <x-validation-errors class="mb-4" />

                @session('status')
                    <div class="mb-4 font-medium text-sm text-[#9BBE4A]">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ isLoading: false }" @submit="isLoading = true">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-[#EEEEEE]">
                            Username / ID
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-zinc-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="username"
                                required
                                autofocus
                                value="{{ old('email') }}"
                                class="pl-10 block w-full sm:text-sm border-[#333333] bg-transparent text-white rounded-md focus:ring-[#9BBE4A] focus:border-[#9BBE4A]"
                                placeholder="Enter your ID"
                            />
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-[#EEEEEE]">
                            Password
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-zinc-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </div>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                class="pl-10 block w-full sm:text-sm border-[#333333] bg-transparent text-white rounded-md focus:ring-[#9BBE4A] focus:border-[#9BBE4A]"
                                placeholder="Enter your password"
                            />
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <label for="remember_me" class="flex items-center">
                            <input type="checkbox" id="remember_me" name="remember" class="rounded border-[#333333] text-[#9BBE4A] focus:ring-[#9BBE4A] bg-transparent shadow-sm" />
                            <span class="ms-2 text-sm text-zinc-400">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm font-medium text-[#9BBE4A] hover:text-[#8AAD39] focus:outline-none focus:underline transition duration-150 ease-in-out" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="isLoading"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-[#9BBE4A] hover:bg-[#8AAD39] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9BBE4A] transition-all duration-200 transform active:scale-95 disabled:opacity-50"
                        >
                            <span x-show="!isLoading">Sign in</span>
                            <span x-show="isLoading" class="flex items-center" style="display: none;">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Signing in...
                            </span>
                        </button>
                    </div>

                    <div class="mt-6 text-center text-sm text-zinc-400">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-[#9BBE4A] hover:text-[#8AAD39] font-medium transition-colors">Sign up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
