@extends('admin.layouts.app')

@section('content')
    <div class="py-2">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Morning Entry</h2>
            <div class="flex items-center gap-3">
                <span class="text-[#9BBE4A] bg-[#9BBE4A]/10 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider animate-pulse">Live</span>
            </div>
        </div>
        
        @livewire('morning-stock-manager')
    </div>
@endsection
