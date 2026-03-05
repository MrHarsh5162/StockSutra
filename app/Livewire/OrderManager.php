<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OrderManager extends Component
{

    public $orderQuantities = []; // item_id => quantity
    public $today;
    public $selectedDate;
    public $orderSavedToday = false;
    public $selectedCategoryId = null;

    public function mount()
    {
        $this->today = Carbon::today()->toDateString();
        $this->selectedDate = $this->today;
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
        
        foreach ($allItems as $item) {
            $this->orderQuantities[$item->id] = '';
        }

        // Just check if any order was created today to show status
        $this->orderSavedToday = Order::whereDate('date', $this->today)->exists();
    }

    public function save()
    {
        $count = 0;
        DB::transaction(function () use (&$count) {
            foreach ($this->orderQuantities as $itemId => $qty) {
                $numQty = (float)$qty;
                if ($numQty > 0) {
                    Order::create([
                        'item_id' => $itemId,
                        'quantity' => $numQty,
                        'date' => $this->today,
                        'received' => false,
                    ]);
                    $count++;
                }
            }
        });

        if ($count > 0) {
            $this->orderSavedToday = true;
            $this->reset('orderQuantities');
            $allItems = Item::all();
            foreach ($allItems as $item) { $this->orderQuantities[$item->id] = ''; }
            $this->dispatch('order-saved', ['message' => 'Orders recorded successfully!']);
        } else {
            $this->dispatch('order-error', ['message' => 'Please enter at least one quantity.']);
        }
    }



    public function render()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        
        $items = Item::with(['unit', 'category'])
            ->when($this->selectedCategoryId, function($query) {
                $query->where('category_id', $this->selectedCategoryId);
            })
            ->orderBy('name')
            ->get();

        $history = Order::with(['item.unit', 'item.category'])
            ->whereDate('date', $this->selectedDate)
            ->latest()
            ->get();

        $categoryMessages = [];
        $formattedDate = Carbon::parse($this->selectedDate)->format('d M, Y');
        
        if ($history->isNotEmpty()) {
            $groupedOrders = $history->groupBy(fn($order) => $order->item->category->name ?? 'Uncategorized');
            
            foreach ($groupedOrders as $catName => $orders) {
                $msg = "*ORDER LIST - $catName - $formattedDate*\n";
                $msg .= "----------------------------------\n";
                foreach ($orders as $order) {
                    $qty = (float)$order->quantity;
                    $unit = $order->item->unit->name ?? '';
                    $msg .= "• *{$order->item->name}*: $qty $unit\n";
                }
                $msg .= "----------------------------------\n";
                $msg .= "Please send these items. Thank you!";
                $categoryMessages[$catName] = $msg;
            }
        }

        return view('admin.order-items.order-manager', [
            'categories' => $categories,
            'items' => $items,
            'history' => $history,
            'categoryMessages' => $categoryMessages
        ]);
    }
}
