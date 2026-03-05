@extends('admin.layouts.app')

@section('content')
    <div class="py-2">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6" x-data>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Items</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your inventory items</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button @click="$dispatch('open-excel-modal')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors border border-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Import
                </button>
                <button @click="$dispatch('open-bulk-modal')" class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-black text-white text-sm font-semibold rounded-lg shadow-sm transition-colors border border-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Bulk Add
                </button>
                <button @click="$dispatch('open-create-modal')" class="inline-flex items-center px-4 py-2 bg-[#9BBE4A] hover:bg-[#8aa842] text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Item
                </button>
            </div>
        </div>
        
        @livewire('product.item-manager')
    </div>
@endsection
