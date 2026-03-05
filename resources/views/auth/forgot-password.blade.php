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

            <div class="bg-[#111111] py-8 px-4 shadow rounded-xl sm:px-10 border border-[#333333] w-full opacity-0 animate-fade-in-up-delayed">
                
                <div class="mb-6 text-sm text-zinc-400 leading-relaxed text-center">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                @session('status')
                    <div class="mb-4 font-medium text-sm text-[#9BBE4A] text-center">
                        {{ $value }}
                    </div>
                @endsession

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6" x-data="{ isLoading: false }" @submit="isLoading = true">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-[#EEEEEE]">
                            Email ID
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-zinc-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
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
                                placeholder="Enter your email ID"
                            />
                        </div>
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="isLoading"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-[#9BBE4A] hover:bg-[#8AAD39] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9BBE4A] transition-all duration-200 transform active:scale-95 disabled:opacity-50"
                        >
                            <span x-show="!isLoading">Email Password Reset Link</span>
                            <span x-show="isLoading" class="flex items-center" style="display: none;">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending Link...
                            </span>
                        </button>
                    </div>

                    <div class="mt-6 text-center text-sm text-zinc-400">
                        <a href="{{ route('login') }}" class="text-[#9BBE4A] hover:text-[#8AAD39] font-medium transition-colors">Return to login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
