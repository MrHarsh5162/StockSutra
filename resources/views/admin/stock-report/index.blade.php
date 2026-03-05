@extends('admin.layouts.app')

@section('content')
    <div class="py-2">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Stock Report</h2>
            <div class="flex items-center gap-3">
                <span class="text-[#EEEEEE]/40 text-xs font-bold uppercase tracking-widest">History Explorer</span>
            </div>
        </div>
        
        @livewire('stock-report-manager')
    </div>
@endsection
