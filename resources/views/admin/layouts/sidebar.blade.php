@php
    $navItems = [
        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
        ['label' => 'Products', 'icon' => 'package', 'subItems' => [
            ['route' => 'admin.categories.index', 'label' => 'Categories'],
            ['route' => 'admin.units.index', 'label' => 'Units'],
            ['route' => 'admin.items.index', 'label' => 'Items'],
        ]],
        ['route' => 'admin.morning-stock.index', 'label' => 'Morning Stock', 'icon' => 'sun'],
        ['route' => 'admin.night-closing.index', 'label' => 'Night Closing', 'icon' => 'moon'],
        ['route' => 'admin.stock-report.index', 'label' => 'Stock Report', 'icon' => 'file-text'],
        ['route' => 'admin.order-items.index', 'label' => 'Order Items', 'icon' => 'shopping-cart'],
    ];

    function getIcon($name, $mobile = false) {
        $size = $mobile ? '24' : '20';
        switch($name) {
            case 'layout-dashboard':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>';
            case 'package':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>';
            case 'sun':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>';
            case 'moon':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>';
            case 'file-text':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>';
            case 'shopping-cart':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>';
            default:
                return '';
        }
    }
@endphp

<!-- Desktop Sidebar -->
<aside class="hidden md:flex w-64 bg-black flex-col shrink-0 border-r border-[#333333] no-print">
    <div class="px-6 py-5 border-b border-white/10 text-center">
        <h1 class="text-[#9BBE4A] text-xl tracking-wide font-bold">StockSutra</h1>
    </div>

    <!-- Nav -->
    <nav class="flex-1 py-4 px-3 space-y-1 overflow-y-auto custom-scrollbar">
        @foreach($navItems as $item)
            @if(isset($item['subItems']))
                @php 
                    $isAnySubActive = collect($item['subItems'])->contains(fn($sub) => request()->routeIs($sub['route']));
                @endphp
                <div x-data="{ open: {{ $isAnySubActive ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" 
                       class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 text-[#EEEEEE] hover:bg-white/5 group">
                        <div class="flex items-center gap-3">
                            <span class="text-gray-400 group-hover:text-[#9BBE4A] transition-colors">
                                {!! getIcon($item['icon']) !!}
                            </span>
                            <span class="font-medium tracking-wide">{{ $item['label'] }}</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180' : ''" class="h-4 w-4 text-gray-500 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-cloak x-collapse class="pl-11 space-y-1">
                        @foreach($item['subItems'] as $sub)
                            @php $isSubActive = request()->routeIs($sub['route']); @endphp
                            <a href="{{ route($sub['route']) }}" 
                               class="block py-2 text-sm transition-colors {{ $isSubActive ? 'text-[#9BBE4A] font-medium' : 'text-gray-400 hover:text-white' }}">
                                {{ $sub['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 {{ $isActive ? 'bg-[#9BBE4A]/15 text-[#9BBE4A] font-semibold' : 'text-[#EEEEEE] hover:bg-white/5 hover:text-white' }} group">
                    <span class="{{ $isActive ? 'text-[#9BBE4A]' : 'text-gray-400 group-hover:text-white' }} transition-colors">
                        {!! getIcon($item['icon']) !!}
                    </span>
                    <span class="tracking-wide">{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>

    <div class="px-6 py-4 border-t border-white/10 text-[#EEEEEE]/40 text-xs">
        © {{ date('Y') }} StockSutra
    </div>
</aside>

<!-- Mobile Bottom Navigation -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 glass-nav border-t border-[#333333] pb-safe z-50 no-print">
    <div class="flex justify-around items-center h-16">
        @foreach($navItems as $item)
            @if(isset($item['route']))
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}" 
                   class="flex flex-col items-center justify-center w-full h-full space-y-1 transition-colors {{ $isActive ? 'text-[#9BBE4A]' : 'text-[#EEEEEE]/70 hover:text-[#EEEEEE]' }}">
                    
                    <div class="{{ $isActive ? 'bg-[#9BBE4A]/15 rounded-full p-1.5' : 'p-1.5' }}">
                        {!! getIcon($item['icon'], true) !!}
                    </div>
                    
                    <span class="text-[10px] whitespace-nowrap leading-none font-medium truncate max-w-[64px]">
                        {{ $item['label'] === 'Morning Stock' ? 'Morning' : ($item['label'] === 'Night Closing' ? 'Closing' : ($item['label'] === 'Stock Report' ? 'Report' : ($item['label'] === 'Order Items' ? 'Orders' : 'Home'))) }}
                    </span>
                </a>
            @endif
        @endforeach
    </div>
</nav>
