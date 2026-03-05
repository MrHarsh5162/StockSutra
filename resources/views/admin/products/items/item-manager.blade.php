<div class="space-y-6">
    <!-- Item List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product Info</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Stock Levels</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($items as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900">{{ $item->name }}</span>
                                    <span class="text-xs text-[#9BBE4A]/70 font-medium tracking-tight uppercase">UNIT: {{ $item->unit->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                    {{ $item->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] uppercase font-bold text-gray-400 w-12">Opening</span>
                                        <span class="text-xs font-bold text-gray-700">{{ $item->opening_stock }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] uppercase font-bold text-gray-400 w-12">Current</span>
                                        <span class="text-sm font-bold {{ $item->current_stock <= 5 ? 'text-red-500' : 'text-[#9BBE4A]' }}">{{ $item->current_stock }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $item->id }})" class="text-[#9BBE4A] hover:text-[#8aa842] mr-3 font-semibold">Edit</button>
                                <button wire:confirm="Are you sure?" wire:click="delete({{ $item->id }})" class="text-red-500 hover:text-red-700 font-semibold">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 11-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <span class="text-gray-500">No items found. Create your inventory items!</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" wire:click="$set('showModal', false)"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle {{ $isBulk ? 'sm:max-w-4xl' : 'sm:max-w-lg' }} sm:w-full border border-gray-200">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-8 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-2xl font-bold leading-6 text-gray-900 mb-6" id="modal-title">
                                    @if($isBulk)
                                        Bulk Add Products
                                    @elseif($isExcel)
                                        Import via Excel/CSV
                                    @else
                                        {{ $editingId ? 'Edit Product' : 'Add New Product' }}
                                    @endif
                                </h3>
                                
                                @if($isBulk)
                                    <!-- Bulk Form (Existing) -->
                                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead>
                                                <tr class="text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                                    <th class="pb-3 pr-2">Category</th>
                                                    <th class="pb-3 px-2">Item Name</th>
                                                    <th class="pb-3 px-2">Unit</th>
                                                    <th class="pb-3 px-2">Stock</th>
                                                    <th class="pb-3 pl-2"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                @foreach($bulkItems as $index => $bulkItem)
                                                    <tr class="group">
                                                        <td class="py-3 pr-2 border-none">
                                                            <select wire:model="bulkItems.{{ $index }}.category_id" class="block w-full px-2 py-1.5 border border-gray-200 rounded-lg text-xs focus:ring-[#9BBE4A]/30 focus:border-[#9BBE4A]">
                                                                <option value="">Select</option>
                                                                @foreach($categories as $category)
                                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error("bulkItems.$index.category_id") <span class="text-[10px] text-red-500 font-medium">{{ $message }}</span> @enderror
                                                        </td>
                                                        <td class="py-3 px-2 border-none">
                                                            <input type="text" wire:model="bulkItems.{{ $index }}.name" class="block w-full px-2 py-1.5 border border-gray-200 rounded-lg text-xs focus:ring-[#9BBE4A]/30 focus:border-[#9BBE4A]" placeholder="Name">
                                                            @error("bulkItems.$index.name") <span class="text-[10px] text-red-500 font-medium">{{ $message }}</span> @enderror
                                                        </td>
                                                        <td class="py-3 px-2 border-none">
                                                            <select wire:model="bulkItems.{{ $index }}.unit_id" class="block w-full px-2 py-1.5 border border-gray-200 rounded-lg text-xs focus:ring-[#9BBE4A]/30 focus:border-[#9BBE4A]">
                                                                <option value="">Unit</option>
                                                                @foreach($units as $unit)
                                                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error("bulkItems.$index.unit_id") <span class="text-[10px] text-red-500 font-medium">{{ $message }}</span> @enderror
                                                        </td>
                                                        <td class="py-3 px-2 border-none">
                                                            <input type="number" step="0.01" wire:model="bulkItems.{{ $index }}.opening_stock" class="block w-32 px-2 py-1.5 border border-gray-200 rounded-lg text-xs focus:ring-[#9BBE4A]/30 focus:border-[#9BBE4A]" placeholder="0.00">
                                                            @error("bulkItems.$index.opening_stock") <span class="text-[10px] text-red-500 font-medium">{{ $message }}</span> @enderror
                                                        </td>
                                                        <td class="py-3 pl-2 border-none text-right">
                                                            @if(count($bulkItems) > 1)
                                                                <button wire:click="removeBulkRow({{ $index }})" class="p-1.5 text-gray-400 hover:text-red-500 transition-colors">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4">
                                        <button wire:click="addBulkRow" class="inline-flex items-center text-xs font-bold text-[#9BBE4A] hover:text-[#8aa842]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Add Another Row
                                        </button>
                                    </div>
                                @elseif($isExcel)
                                    <!-- Excel Import Form -->
                                    <div class="space-y-6">
                                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div class="text-sm text-blue-700">
                                                <p class="font-bold mb-1">Upload a CSV file with these columns:</p>
                                                <p class="text-[11px] opacity-80 uppercase tracking-widest font-mono">Name, Category, Unit, Opening Stock</p>
                                            </div>
                                        </div>

                                        <div 
                                            x-data="{ isUploading: false, progress: 0 }"
                                            x-on:livewire-upload-start="isUploading = true"
                                            x-on:livewire-upload-finish="isUploading = false"
                                            x-on:livewire-upload-error="isUploading = false"
                                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl bg-gray-50 hover:bg-gray-100 transition-colors relative"
                                        >
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="excel-file" class="relative cursor-pointer bg-white rounded-md font-bold text-[#9BBE4A] hover:text-[#8aa842] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#9BBE4A]">
                                                        <span>Upload a file</span>
                                                        <input id="excel-file" wire:model="excelFile" type="file" class="sr-only" accept=".csv">
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">CSV up to 1MB</p>
                                            </div>

                                            <div x-show="isUploading" class="absolute inset-0 bg-white/80 flex flex-col items-center justify-center rounded-2xl">
                                                <div class="w-2/3 bg-gray-200 rounded-full h-2.5 mb-2">
                                                    <div class="bg-[#9BBE4A] h-2.5 rounded-full" :style="`width: ${progress}%`"></div>
                                                </div>
                                                <span class="text-xs font-bold text-gray-500" x-text="`${progress}%` font-medium"></span>
                                            </div>
                                        </div>
                                        
                                        @if($excelFile)
                                            <div class="flex items-center gap-2 text-sm text-[#9BBE4A] font-bold bg-[#9BBE4A]/5 px-3 py-2 rounded-lg border border-[#9BBE4A]/10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Ready to import: {{ $excelFile->getClientOriginalName() }}
                                            </div>
                                        @endif

                                        @error('excelFile') <span class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</span> @enderror

                                        <div class="pt-4 border-t border-gray-100 flex justify-center">
                                            <button wire:click="downloadSample" class="text-blue-600 hover:text-blue-800 text-xs font-bold flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Download CSV Template
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <!-- Single Form -->
                                    <div class="mt-2 space-y-5">
                                        <!-- Category First as requested -->
                                        <div>
                                            <label for="category_id" class="block text-sm font-bold text-gray-700 mb-1.5">Category</label>
                                            <select wire:model="category_id" id="category_id" class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-[#9BBE4A]/30 focus:border-[#9BBE4A] shadow-sm text-sm">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id') <span class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Item Name Second -->
                                        <div>
                                            <label for="name" class="block text-sm font-bold text-gray-700 mb-1.5">Product Name</label>
                                            <input type="text" wire:model="name" id="name" class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-[#9BBE4A]/30 focus:border-[#9BBE4A] shadow-sm text-sm" placeholder="e.g. Tomato, Milk, Chicken">
                                            @error('name') <span class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Unit Third -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="unit_id" class="block text-sm font-bold text-gray-700 mb-1.5">Unit</label>
                                                <select wire:model="unit_id" id="unit_id" class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-[#9BBE4A]/30 focus:border-[#9BBE4A] shadow-sm text-sm">
                                                    <option value="">Select Unit</option>
                                                    @foreach($units as $unit)
                                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('unit_id') <span class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label for="opening_stock" class="block text-sm font-bold text-gray-700 mb-1.5">Opening Stock</label>
                                                <input type="number" step="0.01" wire:model="opening_stock" id="opening_stock" class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-[#9BBE4A]/30 focus:border-[#9BBE4A] shadow-sm text-sm" placeholder="0.00">
                                                @error('opening_stock') <span class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-4 sm:p-8 sm:pt-4 sm:flex sm:flex-row-reverse gap-3">
                        <button wire:click="{{ $isExcel ? 'importExcel' : ($isBulk ? 'saveBulk' : 'save') }}" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg px-6 py-2.5 bg-[#9BBE4A] text-base font-bold text-white hover:bg-[#8aa842] focus:outline-none sm:w-auto sm:text-sm transition-all transform hover:scale-[1.02]">
                            <span wire:loading.remove wire:target="importExcel, save, saveBulk">
                                {{ $editingId ? 'Update Product' : ($isExcel ? 'Import Items' : ($isBulk ? 'Add These Items' : 'Create Product')) }}
                            </span>
                            <span wire:loading wire:target="importExcel, save, saveBulk" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                processing...
                            </span>
                        </button>
                        <button wire:click="$set('showModal', false)" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
