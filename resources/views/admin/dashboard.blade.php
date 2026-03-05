@extends('admin.layouts.app')

@section('content')
    <div class="py-2">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Dashboard Overview</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Stat Card 1 -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 transition-all hover:border-[#9BBE4A]/30 group shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-[#9BBE4A]/10 rounded-lg text-[#9BBE4A]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Morning Stock</h3>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-bold text-gray-900">Added</span>
                    <span class="text-[#9BBE4A] text-xs font-semibold">Today</span>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 transition-all hover:border-blue-500/30 group shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-blue-500/10 rounded-lg text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Pending Orders</h3>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-bold text-gray-900">12</span>
                    <span class="text-blue-600 text-xs font-semibold">Items</span>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 transition-all hover:border-purple-500/30 group shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-purple-500/10 rounded-lg text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Stock Report</h3>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-bold text-gray-900">Updated</span>
                    <span class="text-purple-600 text-xs font-semibold">2h ago</span>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 transition-all hover:border-orange-500/30 group shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-orange-500/10 rounded-lg text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Night Closing</h3>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-bold text-gray-900">Pending</span>
                    <span class="text-orange-600 text-xs font-semibold">Today</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <h3 class="text-gray-900 font-bold">Recent Updates</h3>
                <a href="#" class="text-[#9BBE4A] text-sm hover:underline font-medium">View all</a>
            </div>
            <div class="divide-y divide-gray-50">
                <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50/50 transition-colors">
                    <div class="size-2.5 rounded-full bg-[#9BBE4A] shadow-sm shadow-[#9BBE4A]/20"></div>
                    <div class="flex-1">
                        <p class="text-gray-800 text-sm font-medium">Morning stock recorded for <span class="text-[#9BBE4A]">Main Inventory</span></p>
                        <p class="text-gray-400 text-xs mt-0.5">Today, 09:30 AM</p>
                    </div>
                </div>
                <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50/50 transition-colors">
                    <div class="size-2.5 rounded-full bg-blue-500 shadow-sm shadow-blue-500/20"></div>
                    <div class="flex-1">
                        <p class="text-gray-800 text-sm font-medium">New order items requested for <span class="text-blue-600">Section B</span></p>
                        <p class="text-gray-400 text-xs mt-0.5">Yesterday, 04:15 PM</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
