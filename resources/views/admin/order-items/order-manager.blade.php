<div class="space-y-8">
    <p class="text-gray-500">
        Review base inventory remaining after night closing and plan daily procurement.
    </p>

    <!-- Category Tabs -->
    <div class="no-print">
        <div class="hidden md:flex bg-white border border-gray-200 rounded-2xl p-2 lg:p-3 shadow-sm flex-wrap gap-2 overflow-x-auto custom-scrollbar">
            @foreach($categories as $category)
                <button 
                    wire:click="selectCategory({{ $category->id }})" 
                    class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap {{ $selectedCategoryId == $category->id ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20 scale-105' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 border border-gray-100' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <div class="md:hidden fixed bottom-16 left-0 right-0 z-40 bg-white border-t border-gray-100 p-3 flex gap-2 overflow-x-auto hide-scrollbar scroll-smooth">
            @foreach($categories as $category)
                <button 
                    wire:click="selectCategory({{ $category->id }})" 
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap {{ $selectedCategoryId == $category->id ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20 scale-105' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
        
        <div class="md:hidden h-16 pointer-events-none"></div>
    </div>

    <style>
        .hide-scrollbar {
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }
    </style>

    <!-- Entry Form -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm overflow-hidden">
        <h3 class="text-gray-800 font-bold text-lg mb-6">Create Daily Order</h3>
        
        <!-- Mobile cards -->
        <div class="md:hidden space-y-4">
            @forelse($items as $item)
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-800 font-bold text-lg">{{ $item->name }}</span>
                    <span class="text-gray-500 text-sm bg-white px-2 py-0.5 rounded-lg border border-gray-100">{{ $item->unit->name ?? '' }}</span>
                </div>
                
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Remaining:</span>
                    <span class="text-[#9BBE4A] font-mono font-bold">{{ (float)$item->current_stock }}</span>
                </div>

                <div>
                    <label class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Order Quantity</label>
                    <div class="relative mt-1">
                        <input
                            type="number"
                            step="0.01"
                            wire:model.live="orderQuantities.{{ $item->id }}"
                            placeholder="0.00"
                            class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-blue-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all placeholder-gray-300 font-bold"
                        />
                    </div>
                </div>
            </div>
            @empty
                <div class="bg-gray-50 border border-dashed border-gray-200 rounded-2xl p-10 text-center">
                    <p class="text-gray-400 text-sm">No items in this category.</p>
                </div>
            @endforelse
        </div>

        <!-- Desktop table -->
        <div class="hidden md:block">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left text-gray-400 text-xs uppercase tracking-widest font-bold px-6 py-4">Item Name</th>
                        <th class="text-center text-gray-400 text-xs uppercase tracking-widest font-bold px-6 py-4">Remaining Stock</th>
                        <th class="text-center text-gray-400 text-xs uppercase tracking-widest font-bold px-6 py-4">Unit</th>
                        <th class="text-right text-gray-400 text-xs uppercase tracking-widest font-bold px-6 py-4" style="width: 200px">Order Quantity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($items as $item)
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-6 py-4 text-gray-700 font-bold text-lg">{{ $item->name }}</td>
                        <td class="px-6 py-4 text-[#9BBE4A] font-mono font-bold text-center italic">{{ (float)$item->current_stock }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded-lg uppercase tracking-wider border border-gray-100">{{ $item->unit->name ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <input
                                type="number"
                                step="0.01"
                                wire:model.live="orderQuantities.{{ $item->id }}"
                                placeholder="Enter Qty"
                                class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 w-full text-blue-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 outline-none transition-all placeholder-gray-300 text-center font-black"
                            />
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 bg-white">
                                No items found in this category.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-end mt-8 border-t border-gray-100 pt-6">
            <button
                wire:click="save"
                wire:loading.attr="disabled"
                class="w-full md:w-fit bg-blue-600 text-white px-12 py-4 rounded-xl font-bold hover:bg-blue-700 transition-all cursor-pointer shadow-xl shadow-blue-500/10 disabled:opacity-50 flex items-center justify-center gap-3 group"
            >
                <span wire:loading.remove wire:target="save">Save Order Records</span>
                <span wire:loading wire:target="save" class="animate-spin size-5 border-2 border-white border-t-transparent rounded-full"></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="hidden md:block group-hover:translate-x-1 transition-transform"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    <!-- Sharing Section -->
    @if(count($categoryMessages) > 0)
    <div class="space-y-4">
        <h4 class="text-gray-800 font-bold text-sm">Send Orders to Vendors</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($categoryMessages as $catName => $msg)
            <div class="bg-white border border-gray-100 p-4 rounded-2xl shadow-sm flex flex-col gap-3">
                <div class="flex items-center gap-2">
                    <div class="bg-blue-100 p-1.5 rounded-lg text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                    </div>
                    <span class="text-gray-800 font-black text-xs uppercase tracking-widest">{{ $catName }}</span>
                </div>
                <a href="https://wa.me/?text={{ urlencode($msg) }}" target="_blank" class="flex items-center justify-center gap-2 bg-[#25D366] text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-green-500/20 hover:scale-[1.03] transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 2c-5.51 0-9.989 4.478-9.989 9.989 0 1.76.459 3.413 1.259 4.851l-1.301 4.74h4.86c1.391.758 2.977 1.196 4.665 1.196 5.515 0 10.001-4.484 10.001-9.99 0-5.511-4.486-9.991-10.001-9.991z"/></svg>
                    Share {{ $catName }} List
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-gray-50/50">
                <h3 class="text-gray-800 font-bold">Planned Orders History</h3>
                <div class="flex items-center gap-2">
                    <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest no-print">View Date:</span>
                    <input 
                        type="date" 
                        wire:model.live="selectedDate"
                        max="{{ date('Y-m-d') }}"
                        class="bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-bold text-gray-700 outline-none focus:border-blue-500 transition-all cursor-pointer no-print"
                    />
                    <span class="hidden print:block text-gray-400 text-xs font-mono">{{ \Carbon\Carbon::parse($selectedDate)->format('d M, Y') }}</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase tracking-widest border-b border-gray-100">
                            <th class="px-6 py-4">Item Name</th>
                            <th class="px-6 py-4">Order Quantity</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                        <tbody class="divide-y divide-gray-50">
                        @php
                            $groupedHistory = $history->groupBy(fn($order) => $order->item->category->name ?? 'Uncategorized');
                        @endphp

                        @forelse($groupedHistory as $categoryName => $items)
                        <!-- Category Subheader -->
                        <tr class="bg-gray-50/50">
                            <td colspan="3" class="px-6 py-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $categoryName }}</td>
                        </tr>
                        @foreach($items as $record)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-gray-700 font-bold block">{{ $record->item->name }}</span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $categoryName }}</span>
                            </td>
                            <td class="px-6 py-4 text-blue-600 font-bold font-mono">{{ (float)$record->quantity }} {{ $record->item->unit->name ?? '' }}</td>
                            <td class="px-6 py-4">
                                <span class="text-blue-600 bg-blue-50 px-2 py-1 rounded text-[10px] uppercase font-bold border border-blue-100">Planned</span>
                            </td>
                        </tr>
                        @endforeach
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2 opacity-30">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M8 7v7h8V7"/><path d="M12 7v7"/></svg>
                                    <p class="text-gray-500 font-bold">No orders found for this date.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    <!-- Notifications using Alpine -->
    <div x-data="{ show: false, message: '', type: 'success' }" 
         x-on:order-saved.window="show = true; message = $event.detail.message; type = 'success'; setTimeout(() => show = false, 3000)"
         x-on:order-error.window="show = true; message = $event.detail.message; type = 'error'; setTimeout(() => show = false, 3000)"
         class="fixed bottom-24 md:bottom-10 right-10 z-[100]">
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-10 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-10 opacity-0"
             :class="type === 'success' ? 'bg-blue-600 text-white' : 'bg-red-600 text-white'"
             class="px-6 py-3 rounded-xl font-bold shadow-2xl flex items-center gap-3">
            <template x-if="type === 'success'">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </template>
            <template x-if="type === 'error'">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </template>
            <span x-text="message"></span>
        </div>
    </div>
</div>
