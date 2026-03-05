<div class="space-y-6">
    <!-- Date Picker Card -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-gray-800 font-bold text-lg">Historical Data</h3>
                <p class="text-gray-500 text-sm">Select a date to view stock records.</p>
            </div>
            <div class="relative w-full md:w-64">
                <input 
                    type="date" 
                    wire:model.live="selectedDate"
                    max="{{ date('Y-m-d') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:border-[#9BBE4A] focus:ring-1 focus:ring-[#9BBE4A]/30 outline-none transition-all cursor-pointer"
                />
            </div>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="no-print">
        <div class="hidden md:flex bg-white border border-gray-200 rounded-2xl p-2 lg:p-3 shadow-sm flex-wrap gap-2 overflow-x-auto custom-scrollbar">
            <button 
                wire:click="selectCategory('all')" 
                class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap {{ $selectedCategoryId == 'all' ? 'bg-[#9BBE4A] text-white shadow-lg shadow-[#9BBE4A]/20 scale-105' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 border border-gray-100' }}">
                All Categories
            </button>
            @foreach($categories as $category)
                <button 
                    wire:click="selectCategory({{ $category->id }})" 
                    class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap {{ $selectedCategoryId == $category->id ? 'bg-[#9BBE4A] text-white shadow-lg shadow-[#9BBE4A]/20 scale-105' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 border border-gray-100' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <div class="md:hidden fixed bottom-16 left-0 right-0 z-40 bg-white border-t border-gray-100 p-3 flex gap-2 overflow-x-auto hide-scrollbar scroll-smooth">
            <button 
                wire:click="selectCategory('all')" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap {{ $selectedCategoryId == 'all' ? 'bg-[#9BBE4A] text-white shadow-lg shadow-[#9BBE4A]/20 scale-105' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">
                All
            </button>
            @foreach($categories as $category)
                <button 
                    wire:click="selectCategory({{ $category->id }})" 
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap {{ $selectedCategoryId == $category->id ? 'bg-[#9BBE4A] text-white shadow-lg shadow-[#9BBE4A]/20 scale-105' : 'bg-gray-50 text-gray-500 border border-gray-100' }}">
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
    </style>    <!-- Print Styles -->
    <style>
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { 
                background: white !important; 
                padding: 0 !important;
                margin: 0 !important;
            }
            .printable-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            th, td {
                border: 1px solid #e2e8f0 !important;
                padding: 8px 12px !important;
                font-size: 11px !important;
                color: black !important;
            }
            th {
                background-color: #f8fafc !important;
                font-weight: bold !important;
                text-transform: uppercase;
            }
        }
        .print-only { display: none; }
    </style>

    <!-- Professional Print Header -->
    <div class="print-only mb-10">
        <div class="flex justify-between items-start border-b-4 border-gray-800 pb-6">
            <div>
                <h1 class="text-3xl font-black tracking-tighter text-gray-900">STOCK SUTRA</h1>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Smart Inventory Solutions</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-gray-800">DAILY STOCK REPORT</h2>
                <p class="text-sm font-mono text-gray-500 mt-1">{{ \Carbon\Carbon::parse($selectedDate)->format('D, d F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Main Report Section -->
    <div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm printable-card">
        <!-- Web Header -->
        <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/30 flex flex-col sm:flex-row items-center justify-between gap-4 no-print">
            <div>
                <h3 class="text-gray-800 font-bold text-lg">Stock Summary</h3>
                <p class="text-gray-400 text-xs font-medium">{{ \Carbon\Carbon::parse($selectedDate)->format('d F, Y') }}</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a 
                    href="https://wa.me/?text={{ $this->getShareMessage() }}" 
                    target="_blank"
                    class="flex-1 sm:flex-initial bg-[#25D366] text-white px-5 py-2.5 rounded-2xl text-sm font-bold shadow-lg shadow-green-500/20 hover:scale-[1.03] transition-all flex items-center justify-center gap-2 group {{ count($reportData) == 0 ? 'pointer-events-none opacity-50' : '' }}"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" class="group-hover:scale-110 transition-transform"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    Share
                </a>

                <button 
                    wire:click="downloadPDF" 
                    wire:loading.attr="disabled"
                    class="flex-1 sm:flex-initial bg-blue-600 text-white px-6 py-2.5 rounded-2xl text-sm font-bold shadow-lg shadow-blue-500/20 hover:scale-[1.03] transition-all flex items-center justify-center gap-2 group disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="downloadPDF" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="group-hover:translate-y-0.5 transition-transform"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download PDF
                    </span>
                    <span wire:loading wire:target="downloadPDF" class="animate-spin size-4 border-2 border-white border-t-transparent rounded-full"></span>
                </button>

                <button onclick="window.print()" class="flex-1 sm:flex-initial bg-[#9BBE4A] text-white px-6 py-2.5 rounded-2xl text-sm font-bold shadow-lg shadow-[#9BBE4A]/20 hover:scale-[1.03] transition-all flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Print Report
                </button>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-gray-400 text-[10px] uppercase tracking-widest font-black border-b border-gray-100">Item Description</th>
                        <th class="px-6 py-5 text-center text-gray-400 text-[10px] uppercase tracking-widest font-black border-b border-gray-100">Opening</th>
                        <th class="px-6 py-5 text-center text-gray-400 text-[10px] uppercase tracking-widest font-black border-b border-gray-100">Total Avail.</th>
                        <th class="px-6 py-5 text-center text-gray-400 text-[10px] uppercase tracking-widest font-black border-b border-gray-100">Consum.</th>
                        <th class="px-6 py-5 text-center text-gray-800 text-[10px] uppercase tracking-widest font-black border-b border-gray-100">Closing</th>
                        <th class="px-8 py-5 text-right text-gray-400 text-[10px] uppercase tracking-widest font-black border-b border-gray-100">Unit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white">
                    @forelse($reportData as $row)
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-8 py-5">
                            <span class="text-gray-800 font-black text-base block leading-tight">{{ $row->name }}</span>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5 no-print">{{ $selectedCategoryId == 'all' ? 'Inventory Item' : '' }}</span>
                        </td>
                        <td class="px-6 py-5 text-center text-gray-400 font-mono font-bold text-xs">{{ (float)$row->starting }}</td>
                        <td class="px-6 py-5 text-center text-gray-500 font-mono font-bold bg-gray-50/30">{{ (float)$row->total }}</td>
                        <td class="px-6 py-5 text-center text-orange-600 font-mono font-bold">{{ (float)$row->consumed }}</td>
                        <td class="px-6 py-5 text-center text-[#9BBE4A] font-extrabold text-lg">{{ (float)$row->closing }}</td>
                        <td class="px-8 py-5 text-right">
                            <span class="bg-gray-100 text-gray-500 text-[9px] font-black px-2.5 py-1 rounded-lg uppercase border border-gray-100 shadow-sm">{{ $row->unit }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                <p class="text-gray-900 font-black mt-4 uppercase tracking-widest text-xs">No records for this date</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Official Signatories Section -->
    <div class="mt-12 no-print">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white border border-gray-100 p-6 rounded-3xl shadow-sm">
                <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Grand Total Consumption</span>
                <p class="text-3xl font-black text-orange-600 mt-1">{{ collect($reportData)->sum('consumed') }}</p>
            </div>
            <div class="bg-white border border-gray-100 p-6 rounded-3xl shadow-sm">
                <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Items Count</span>
                <p class="text-3xl font-black text-gray-800 mt-1">{{ count($reportData) }}</p>
            </div>
        </div>
    </div>

    <!-- Print Footer -->
    <div class="print-only mt-24">
        <div class="grid grid-cols-2 gap-20">
            <div class="text-center">
                <div class="border-t-2 border-gray-900 pt-3">
                    <p class="text-[10px] font-black uppercase tracking-widest">Inventory Manager</p>
                    <p class="text-[8px] text-gray-400 italic mt-1">(Sign & Date)</p>
                </div>
            </div>
            <div class="text-center">
                <div class="border-t-2 border-gray-900 pt-3">
                    <p class="text-[10px] font-black uppercase tracking-widest">Office Head</p>
                    <p class="text-[8px] text-gray-400 italic mt-1">(Seal & Verify)</p>
                </div>
            </div>
        </div>
        <div class="mt-16 pt-4 border-t border-gray-100 flex justify-between items-center text-[8px] text-gray-300 italic font-mono uppercase tracking-widest">
            <span>Stock Sutra ERP System Generated</span>
            <span>Ref ID: {{ now()->timestamp }} | Printed: {{ now()->format('d M Y, h:i A') }}</span>
        </div>
    </div>
