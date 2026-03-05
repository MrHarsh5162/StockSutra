<div class="space-y-6">
    <!-- Action Bar -->
    <div class="flex justify-start">
        <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-[#9BBE4A]/10 text-[#9BBE4A] hover:bg-[#9BBE4A]/20 text-sm font-semibold rounded-lg transition-colors border border-[#9BBE4A]/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Unit
        </button>
    </div>

    <!-- Unit Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Short Code</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Full Name</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($units as $unit)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-[#9BBE4A] bg-[#9BBE4A]/5 px-2 py-1 rounded">{{ $unit->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $unit->fullname ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $unit->id }})" class="text-[#9BBE4A] hover:text-[#8aa842] mr-3 font-semibold">Edit</button>
                            <button wire:confirm="Deleting a unit might affect items using it. Continue?" wire:click="delete({{ $unit->id }})" class="text-red-500 hover:text-red-700 font-semibold">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-gray-500">No units found. Add units like KG, Tray, etc.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" wire:click="$set('showModal', false)"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-xl font-bold leading-6 text-gray-900 mb-4" id="modal-title">
                                    {{ $editingId ? 'Edit Unit' : 'Add New Unit' }}
                                </h3>
                                <div class="mt-2 space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Short Code (e.g. KG)</label>
                                        <input type="text" wire:model="name" id="name" class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#9BBE4A] focus:border-[#9BBE4A] shadow-sm text-sm" placeholder="e.g. KG, BTL, TRAY">
                                        @error('name') <span class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="fullname" class="block text-sm font-bold text-gray-700 mb-1">Full Name (Optional)</label>
                                        <input type="text" wire:model="fullname" id="fullname" class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#9BBE4A] focus:border-[#9BBE4A] shadow-sm text-sm" placeholder="e.g. Kilogram, Bottle, Tray">
                                        @error('fullname') <span class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <button wire:click="save" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-[#9BBE4A] text-base font-semibold text-white hover:bg-[#8aa842] focus:outline-none sm:w-auto sm:text-sm transition-colors">
                            {{ $editingId ? 'Update' : 'Save' }}
                        </button>
                        <button wire:click="$set('showModal', false)" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
