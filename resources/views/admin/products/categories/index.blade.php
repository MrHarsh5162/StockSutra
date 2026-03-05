@extends('admin.layouts.app')

@section('content')
    <div class="py-2">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Categories</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your product categories</p>
            </div>
        </div>
        
        @livewire('product.category-manager')
    </div>
@endsection
