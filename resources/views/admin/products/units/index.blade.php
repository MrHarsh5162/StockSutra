@extends('admin.layouts.app')

@section('content')
    <div class="py-2">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Units</h2>
                <p class="text-sm text-gray-500 mt-1">Manage measurement units (KG, Tray, Pcs, etc.)</p>
            </div>
        </div>
        
        @livewire('product.unit-manager')
    </div>
@endsection
