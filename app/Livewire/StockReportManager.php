<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\NightClosing;
use Carbon\Carbon;
use Livewire\Component;

class StockReportManager extends Component
{
    public $selectedDate;
    public $selectedCategoryId = 'all';

    public function mount()
    {
        $this->selectedDate = Carbon::today()->toDateString();
    }

    public function selectCategory($id)
    {
        $this->selectedCategoryId = $id;
    }

    protected function getReportData()
    {
        $closings = NightClosing::whereDate('entry_date', $this->selectedDate)
            ->when($this->selectedCategoryId !== 'all', function($query) {
                $query->whereHas('item', function($q) {
                    $q->where('category_id', $this->selectedCategoryId);
                });
            })->get()->keyBy('item_id');

        $morningAdditions = \App\Models\MorningStock::whereDate('entry_date', $this->selectedDate)
            ->get()->keyBy('item_id');

        $items = Item::with(['unit', 'category'])
            ->when($this->selectedCategoryId !== 'all', function($query) {
                $query->where('category_id', $this->selectedCategoryId);
            })->get();

        $reportData = [];
        foreach ($items as $item) {
            $closing = $closings->get($item->id);
            $morning = $morningAdditions->get($item->id);
            if (!$closing && !$morning) continue;

            $totalAvailable = $closing ? (float)$closing->opening_quantity : 0;
            $addedToday = $morning ? (float)$morning->quantity_received : 0;
            $startingStock = $totalAvailable - $addedToday;
            
            $reportData[] = (object)[
                'name' => $item->name,
                'unit' => $item->unit->name ?? '',
                'starting' => $startingStock,
                'added' => $addedToday,
                'total' => $totalAvailable,
                'consumed' => $closing ? (float)$closing->consumed_quantity : 0,
                'closing' => $closing ? (float)$closing->closing_quantity : 0
            ];
        }

        return collect($reportData);
    }

    public function downloadPDF()
    {
        $catName = 'All_Categories';
        if ($this->selectedCategoryId !== 'all') {
            $category = \App\Models\Category::find($this->selectedCategoryId);
            $catName = $category ? str_replace(' ', '_', $category->name) : 'Category';
        }

        $reportData = $this->getReportData();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.stock-report.pdf-template', [
            'reportData' => $reportData,
            'date' => Carbon::parse($this->selectedDate)->format('d-M-Y'),
            'category_name' => str_replace('_', ' ', $catName)
        ]);

        $fileName = "{$catName}_{$this->selectedDate}.pdf";
        
        return response()->streamDownload(
            fn() => print($pdf->output()), 
            $fileName
        );
    }

    public function getShareMessage()
    {
        $reportData = $this->getReportData();
        if ($reportData->isEmpty()) return "";

        $dateStr = Carbon::parse($this->selectedDate)->format('d M, Y');
        
        $catLabel = 'All Categories';
        if ($this->selectedCategoryId !== 'all') {
            $category = \App\Models\Category::find($this->selectedCategoryId);
            $catLabel = $category ? $category->name : 'Category';
        }

        $message = "*STOCK REPORT - {$dateStr}*\n";
        $message .= "*Category: {$catLabel}*\n";
        $message .= "--------------------------\n";

        foreach ($reportData as $row) {
            $message .= "* {$row->name}: *{$row->closing} {$row->unit}*\n";
        }

        return rawurlencode($message);
    }

    public function render()
    {
        return view('admin.stock-report.stock-report-manager', [
            'categories' => \App\Models\Category::orderBy('name')->get(),
            'reportData' => $this->getReportData(),
        ]);
    }
}
