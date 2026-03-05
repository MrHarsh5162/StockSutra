<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\NightClosing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NightClosingManager extends Component
{

    public $closingQuantities = []; // item_id => quantity
    public $today;
    public $closingDone = false;
    public $selectedCategoryId = null;

    public function mount()
    {
        $this->today = Carbon::today()->toDateString();
        $this->loadData();
        
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
        $allItems = Item::all();
        
        $closings = NightClosing::whereDate('entry_date', $this->today)->get()->keyBy('item_id');

        foreach ($allItems as $item) {
            if ($closings->has($item->id)) {
                $this->closingQuantities[$item->id] = (string)$closings[$item->id]->closing_quantity;
            } else {
                $this->closingQuantities[$item->id] = '';
            }
        }

        $this->closingDone = $closings->isNotEmpty();
    }

    public function save()
    {
        $allItems = Item::all();
        DB::transaction(function () use ($allItems) {
            foreach ($allItems as $item) {
                $closingQty = (float)($this->closingQuantities[$item->id] ?: 0);
                $openingQty = (float)$item->current_stock;
                $consumedQty = max(0, $openingQty - $closingQty);

                NightClosing::updateOrCreate(
                    ['item_id' => $item->id, 'entry_date' => $this->today],
                    [
                        'opening_quantity' => $openingQty,
                        'closing_quantity' => $closingQty,
                        'consumed_quantity' => $consumedQty,
                    ]
                );

                // Carry forward: Today's closing becomes BOTH Tomorrow's opening AND Today's current stock
                // This ensures the "Order Page" (which shows remaining stock) sees the correct value.
                $item->update([
                    'opening_stock' => $closingQty, // For tomorrow morning
                    'current_stock' => $closingQty, // For immediate ordering/view
                ]);
            }
        });

        $this->closingDone = true;
        $this->dispatch('closing-saved', ['message' => 'Night closing records saved successfully!']);
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

        return view('admin.night-closing.night-closing-manager', [
            'categories' => $categories,
            'items' => $items,
        ]);
    }
}
