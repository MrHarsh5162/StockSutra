<div class="space-y-6">
    <p class="text-gray-500">
        Record the final stock quantities at the end of the day. Consumed stock is calculated automatically.
    </p>

    <!-- Category Tabs -->
    <div class="no-print">
        <div class="hidden md:flex bg-white border border-gray-200 rounded-2xl p-2 lg:p-3 shadow-sm flex-wrap gap-2 overflow-x-auto custom-scrollbar">
            @foreach($categories as $category)
                <button 
                    wire:click="selectCategory({{ $category->id }})" 
                    class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap {{ $selectedCategoryId == $category->id ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20 scale-105' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 border border-gray-100' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <div class="md:hidden fixed bottom-16 left-0 right-0 z-40 bg-white border-t border-gray-100 p-3 flex gap-2 overflow-x-auto hide-scrollbar scroll-smooth">
            @foreach($categories as $category)
                <button 
                    wire:click="selectCategory({{ $category->id }})" 
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap {{ $selectedCategoryId == $category->id ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20 scale-105' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">
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

    <!-- Mobile view -->
    <div class="md:hidden space-y-4">
        @forelse($items as $item)
        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-800 font-bold text-lg">{{ $item->name }}</span>
                <span class="text-gray-500 text-sm bg-gray-50 px-2 py-0.5 rounded-lg border border-gray-100">{{ $item->unit->name ?? '' }}</span>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-gray-50/50 p-3 rounded-xl border border-gray-100">
                    <p class="text-gray-400 text-[10px] uppercase font-bold tracking-widest">Opening</p>
                    <p class="text-gray-700 font-mono font-medium">{{ (float)$item->current_stock }}</p>
                </div>
                <div class="bg-gray-50/50 p-3 rounded-xl border border-gray-100">
                    <p class="text-gray-400 text-[10px] uppercase font-bold tracking-widest">Consumption</p>
                    @php $consumed = max(0, (float)$item->current_stock - (float)($closingQuantities[$item->id] ?: 0)); @endphp
                    <p class="text-orange-600 font-mono font-bold">{{ $consumed }}</p>
                </div>
            </div>

            <div>
                <label class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Final Closing</label>
                <div class="relative mt-1.5">
                    <input
                        type="number"
                        step="0.01"
                        wire:model.live="closingQuantities.{{ $item->id }}"
                        placeholder="0.00"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:border-orange-500 focus:ring-1 focus:ring-orange-500/30 outline-none transition-all placeholder-gray-300 font-bold"
                    />
                </div>
            </div>
        </div>
        @empty
            <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-10 text-center">
                <p class="text-gray-400 text-sm">No items in this category.</p>
            </div>
        @endforelse
    </div>

    <!-- Desktop view -->
    <div class="hidden md:block bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="text-left text-gray-400 text-xs uppercase tracking-widest font-bold px-6 py-4">Item Name</th>
                    <th class="text-center text-gray-400 text-xs uppercase tracking-widest font-bold px-6 py-4">Opening (Start)</th>
                    <th class="text-center text-gray-400 text-xs uppercase tracking-widest font-bold px-6 py-4" style="width: 200px">Final Closing</th>
                    <th class="text-center text-gray-400 text-xs uppercase tracking-widest font-bold px-6 py-4">Consumed</th>
                    <th class="text-right text-gray-400 text-xs uppercase tracking-widest font-bold px-6 py-4">Unit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($items as $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-gray-700 font-bold text-lg">{{ $item->name }}</td>
                    <td class="px-6 py-4 text-center text-gray-500 font-mono italic">{{ (float)$item->current_stock }}</td>
                    <td class="px-6 py-4 text-center">
                        <input
                            type="number"
                            step="0.01"
                            wire:model.live="closingQuantities.{{ $item->id }}"
                            placeholder="0.00"
                            class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 w-32 text-orange-600 focus:border-orange-500 focus:ring-1 focus:ring-orange-500/30 outline-none transition-all text-center placeholder-gray-300 font-black"
                        />
                    </td>
                    <td class="px-6 py-4 text-center font-mono font-bold text-orange-600 text-lg">
                        @php $consumed = (float)$item->current_stock - (float)($closingQuantities[$item->id] ?: 0); @endphp
                        {{ max(0, $consumed) }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded-lg uppercase tracking-wider border border-gray-100">{{ $item->unit->name ?? '' }}</span>
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
            class="w-full md:w-fit bg-orange-600 text-white px-12 py-4 rounded-xl font-bold hover:bg-orange-700 transition-all cursor-pointer shadow-xl shadow-orange-500/10 disabled:opacity-50 flex items-center justify-center gap-3 group"
        >
            <span wire:loading.remove wire:target="save">
                {{ $closingDone ? 'Update Night Records' : 'Save Night Closing' }}
            </span>
            <span wire:loading wire:target="save" class="animate-spin size-5 border-2 border-white border-t-transparent rounded-full"></span>
            
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="group-hover:rotate-12 transition-transform"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
        </button>

        @if($closingDone)
        <p class="text-orange-600 text-sm flex items-center gap-2 font-bold bg-orange-50 w-fit px-4 py-2 rounded-lg border border-orange-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            Night closing has been finalized for today
        </p>
        @endif
    </div>

    <!-- Notifications using Alpine -->
    <div x-data="{ show: false, message: '' }" 
         x-on:closing-saved.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
         class="fixed bottom-24 md:bottom-10 right-10 z-[100]">
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-10 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-10 opacity-0"
             class="bg-orange-600 text-white px-6 py-3 rounded-xl font-bold shadow-2xl flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            <span x-text="message"></span>
        </div>
    </div>
</div>
