<header class="bg-white border-b border-gray-200 px-4 md:px-6 py-4 flex items-center justify-between z-30 relative no-print">
    <!-- Mobile Logo -->
    <div class="md:hidden">
        <h1 class="text-[#9BBE4A] text-lg font-bold tracking-tight">StockSutra</h1>
    </div>
    <div class="flex items-center gap-4 ml-auto">
        <!-- Profile Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
            <div>
                <button @click="open = ! open" class="flex items-center gap-3 focus:outline-none transition-all">
                    <div class="hidden md:flex flex-col items-end mr-1">
                        <span class="text-sm font-semibold text-gray-900 leading-none">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wider mt-1">{{ Auth::user()->email }}</span>
                    </div>
                    <img class="h-9 w-9 rounded-full object-cover shadow-sm" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    <svg class="h-4 w-4 text-gray-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Dropdown Menu -->
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 origin-top-right bg-white rounded-xl shadow-2xl z-50 focus:outline-none"
                 style="display: none;">
                
                <!-- Account Info (Mobile) -->
                <div class="px-4 py-3 md:hidden">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs font-medium text-gray-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                </div>

                <div class="py-1">
                    <a href="{{ route('profile.show') }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-[#9BBE4A]/5 hover:text-[#9BBE4A] transition-colors">
                        <svg class="mr-3 h-4 w-4 text-gray-400 group-hover:text-[#9BBE4A]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile Settings
                    </a>
                </div>

                <div class="py-1">
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <button type="submit" class="group flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="mr-3 h-4 w-4 text-red-400 group-hover:text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
