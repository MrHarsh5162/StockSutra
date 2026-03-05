<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\MorningStock;
use App\Models\Order;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MorningStockManager extends Component
{
    public $localAdded = []; // item_id => quantity (string)
    public $morningStockDone = false;
    public $today;
    public $selectedCategoryId = null;

    public function mount()
    {
        $this->today = Carbon::today()->toDateString();
        $this->loadData();
        
        // Default to first category if available
        $firstCategory = \App\Models\Category::orderBy('name')->first();
        if ($firstCategory) {
            $this->selectedCategoryId = $firstCategory->id;
        }
    }

    public function selectCategory($id)
    {
        $this->selectedCategoryId = $id;
    }

    public function loadData()
    {
        $items = Item::all();
        foreach ($items as $item) {
            // Check if there's already an entry for today
            $alreadyAdded = MorningStock::where('item_id', $item->id)
                ->whereDate('entry_date', $this->today)
                ->sum('quantity_received');
            
            $this->localAdded[$item->id] = $alreadyAdded > 0 ? (string)$alreadyAdded : '';
        }

        $this->morningStockDone = MorningStock::whereDate('entry_date', $this->today)->exists();
    }



    public function save()
    {
        DB::transaction(function () {
            // Clear today's entries if we are updating
            MorningStock::whereDate('entry_date', $this->today)->delete();
            
            // Reset stock to opening for items before applying modifications
            // This is a bit tricky if we don't have a record of stock changes.
            // But let's follow the user's "Opening + Added" logic.
            // Opening stock is carried over from last night.

            foreach ($this->localAdded as $itemId => $qty) {
                if (empty($qty)) continue;
                $numQty = (float)$qty;
                if ($numQty > 0) {
                    MorningStock::create([
                        'item_id' => $itemId,
                        'quantity_received' => $numQty,
                        'entry_date' => $this->today,
                    ]);

                    $item = Item::find($itemId);
                    if ($item) {
                        $item->current_stock = $item->opening_stock + $numQty;
                        $item->save();
                    }
                }
            }
        });

        $this->morningStockDone = true;
        $this->dispatch('stock-saved', ['message' => 'Morning stock saved successfully!']);
    }

    public function render()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        
        $items = Item::with(['unit'])
            ->when($this->selectedCategoryId, function($query) {
                $query->where('category_id', $this->selectedCategoryId);
            })
            ->orderBy('name')
            ->get();

        return view('admin.morning-stock.morning-stock-manager', [
            'categories' => $categories,
            'items' => $items,
        ]);
    }
}
