<div class="space-y-6">
    <p class="text-gray-500">
        Add stock received in the morning. Opening stock is carried over from last night's closing.
    </p>


    <!-- Category Tabs -->
    <div class="no-print">
        <!-- Desktop: Regular position at top -->
        <div class="hidden md:flex bg-white border border-gray-200 rounded-2xl p-2 lg:p-3 shadow-sm flex-wrap gap-2 overflow-x-auto custom-scrollbar">
            @foreach($categories as $category)
                <button 
                    wire:click="selectCategory({{ $category->id }})" 
                    class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap {{ $selectedCategoryId == $category->id ? 'bg-[#9BBE4A] text-white shadow-lg shadow-[#9BBE4A]/20 scale-105' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 border border-gray-100' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <!-- Mobile: Sticky above bottom navigation -->
        <div class="md:hidden fixed bottom-16 left-0 right-0 z-40 bg-white border-t border-gray-100 p-3 flex gap-2 overflow-x-auto hide-scrollbar scroll-smooth">
            @foreach($categories as $category)
                <button 
                    wire:click="selectCategory({{ $category->id }})" 
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap {{ $selectedCategoryId == $category->id ? 'bg-[#9BBE4A] text-white shadow-lg shadow-[#9BBE4A]/20 scale-105' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
        
        <!-- Mobile Bottom Spacing for Sticky Bar -->
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

    <!-- Mobile view cards (Only for selected category) -->
    <div class="md:hidden space-y-4">
        @forelse($items as $item)
        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-800 font-bold text-lg">{{ $item->name }}</span>
                <span class="text-gray-500 text-sm bg-gray-50 px-2 py-0.5 rounded-lg border border-gray-100">{{ $item->unit->name ?? '' }}</span>
            </div>
            <p class="text-gray-400 text-xs mb-4">
                Opening: <span class="text-gray-700 font-medium">{{ (float)$item->opening_stock }}</span> {{ $item->unit->name ?? '' }}
            </p>
            <div>
                <label class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Quantity to add</label>
                <div class="relative mt-1.5">
                    <input
                        type="number"
                        step="0.01"
                        wire:model.live="localAdded.{{ $item->id }}"
                        placeholder="0.00"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:border-[#9BBE4A] focus:ring-1 focus:ring-[#9BBE4A]/30 outline-none transition-all placeholder-gray-300 font-bold"
                    />
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400 text-[10px] font-bold">
                        <span class="hidden xs:inline">TOT: </span>{{ (float)$item->opening_stock + (float)($localAdded[$item->id] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
        @empty
            <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-10 text-center">
                <p class="text-gray-400 text-sm">No items in this category.</p>
            </div>
        @endforelse
    </div>

    <!-- Desktop table (Only for selected category) -->
    <div class="hidden md:block rounded-2xl overflow-hidden shadow-sm border border-gray-200">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="text-left text-gray-400 text-[10px] uppercase tracking-widest font-bold px-6 py-4">Item Name</th>
                    <th class="text-center text-gray-400 text-[10px] uppercase tracking-widest font-bold px-6 py-4">Opening Stock</th>
                    <th class="text-center text-gray-400 text-[10px] uppercase tracking-widest font-bold px-6 py-4">Add Quantity</th>
                    <th class="text-center text-gray-400 text-[10px] uppercase tracking-widest font-bold px-6 py-4">Unit</th>
                    <th class="text-right text-gray-400 text-[10px] uppercase tracking-widest font-bold px-6 py-4">Final Total</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($items as $item)
                    @php $added = (float)($localAdded[$item->id] ?? 0); @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors border-b border-gray-100 last:border-b-0">
                        <td class="px-6 py-4">
                            <span class="text-gray-700 font-bold">{{ $item->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500 font-mono italic">{{ (float)$item->opening_stock }}</td>
                        <td class="px-6 py-4 flex justify-center">
                            <input
                                type="number"
                                step="0.01"
                                wire:model.live="localAdded.{{ $item->id }}"
                                placeholder="0.00"
                                class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 w-32 text-[#9BBE4A] focus:border-[#9BBE4A] focus:ring-1 focus:ring-[#9BBE4A]/30 outline-none transition-all placeholder-gray-300 text-center font-black"
                            />
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded-lg uppercase tracking-wider border border-gray-100">{{ $item->unit->name ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-[#9BBE4A] font-mono text-lg">
                            {{ (float)$item->opening_stock + $added }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 bg-white">
                            No items found in this category.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex flex-col gap-4 mt-8">
        <button
            wire:click="save"
            wire:loading.attr="disabled"
            class="w-full md:w-fit bg-[#9BBE4A] text-white px-12 py-4 rounded-xl font-bold hover:bg-[#8aac3e] transition-all cursor-pointer shadow-xl shadow-[#9BBE4A]/20 disabled:opacity-50 flex items-center justify-center gap-3 group"
        >
            <span wire:loading.remove wire:target="save">
                {{ $morningStockDone ? 'Update Morning Stock' : 'Save Morning Stock' }}
            </span>
            <span wire:loading wire:target="save" class="animate-spin size-5 border-2 border-white border-t-transparent rounded-full"></span>
            
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="hidden md:block group-hover:translate-x-1 transition-transform"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>

        @if($morningStockDone)
        <p class="text-[#9BBE4A] text-sm flex items-center gap-2 font-bold bg-[#9BBE4A]/10 w-fit px-4 py-2 rounded-lg border border-[#9BBE4A]/20">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            Morning stock has been saved for today
        </p>
        @endif
    </div>

    <!-- Notification system -->
    <div x-data="{ show: false, message: '' }" 
         x-on:stock-saved.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
         class="fixed bottom-24 md:bottom-10 right-10 z-[100]">
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-10 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-10 opacity-0"
             class="bg-[#9BBE4A] text-white px-6 py-3 rounded-xl font-bold shadow-2xl flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            <span x-text="message"></span>
        </div>
    </div>
</div>
