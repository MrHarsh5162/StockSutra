@extends('admin.layouts.app')

@section('content')
    <div class="py-2">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Order Items</h2>
            <div class="flex items-center gap-3">
                <span class="text-blue-500 bg-blue-500/10 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider animate-pulse transition-all">Planning</span>
            </div>
        </div>
        
        @livewire('order-manager')
    </div>
@endsection
