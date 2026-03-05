<?php

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Item;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithFileUploads;

class ItemManager extends Component
{
    use WithFileUploads;

    public $name;
    public $category_id;
    public $unit_id;
    public $opening_stock = 0;
    
    // Bulk Add
    public $bulkItems = []; // Array of ['name' => '', 'category_id' => '', 'unit_id' => '', 'opening_stock' => 0]
    public $isBulk = false;
    public $isExcel = false;
    public $excelFile;

    public $showModal = false;
    public $editingId = null;

    protected $listeners = [
        'open-create-modal' => 'create',
        'open-bulk-modal' => 'openBulk',
        'open-excel-modal' => 'openExcel'
    ];

    protected $rules = [
        'name' => 'required|string|max:255|unique:items,name',
        'category_id' => 'required|exists:categories,id',
        'unit_id' => 'required|exists:units,id',
        'opening_stock' => 'required|numeric|min:0',
    ];

    public function create()
    {
        $this->isBulk = false;
        $this->isExcel = false;
        $this->reset(['name', 'category_id', 'unit_id', 'opening_stock', 'editingId', 'bulkItems', 'excelFile']);
        $this->showModal = true;
    }

    public function openBulk()
    {
        $this->isBulk = true;
        $this->isExcel = false;
        $this->editingId = null;
        $this->bulkItems = [
            ['name' => '', 'category_id' => '', 'unit_id' => '', 'opening_stock' => 0]
        ];
        $this->showModal = true;
    }

    public function openExcel()
    {
        $this->isBulk = false;
        $this->isExcel = true;
        $this->editingId = null;
        $this->reset(['excelFile']);
        $this->showModal = true;
    }

    public function importExcel()
    {
        $this->validate([
            'excelFile' => 'required|mimes:csv,txt|max:1024',
        ]);

        $path = $this->excelFile->getRealPath();
        $file = fopen($path, 'r');
        
        $header = fgetcsv($file); // Skip header
        // Expecting: Name, Category, Unit, Opening Stock
        
        $itemsAdded = 0;
        $errors = [];
        $rowNum = 2;

        while (($row = fgetcsv($file)) !== FALSE) {
            if (count($row) < 3) continue;

            $itemName = trim($row[0] ?? '');
            $categoryName = trim($row[1] ?? '');
            $unitName = trim($row[2] ?? '');
            $stock = isset($row[3]) ? (float)trim($row[3]) : 0;

            if (empty($itemName) || empty($categoryName) || empty($unitName)) {
                if (!empty($itemName)) $errors[] = "Row $rowNum: Category or Unit missing.";
                $rowNum++;
                continue;
            }

            // Check duplicate
            if (Item::where('name', $itemName)->exists()) {
                $errors[] = "Row $rowNum: '$itemName' already exists.";
                $rowNum++;
                continue;
            }

            // Find Category
            $category = Category::where('name', 'like', $categoryName)->first();
            if (!$category) {
                $category = Category::create(['name' => $categoryName]);
            }

            // Find Unit
            $unit = Unit::where('name', 'like', $unitName)->first();
            if (!$unit) {
                // Default to first unit or create if user wants?
                // For simplicity, let's create a placeholder unit if not found
                $unit = Unit::firstOrCreate(['name' => strtoupper($unitName)], ['fullname' => $unitName]);
            }

            Item::create([
                'name' => $itemName,
                'category_id' => $category->id,
                'unit_id' => $unit->id,
                'opening_stock' => $stock,
                'current_stock' => $stock,
            ]);

            $itemsAdded++;
            $rowNum++;
        }

        fclose($file);

        $this->showModal = false;
        
        if (count($errors) > 0) {
            $msg = "$itemsAdded items added. Note: " . implode(" ", array_slice($errors, 0, 3));
            if (count($errors) > 3) $msg .= "... and more errors.";
            $this->dispatch('item-saved', ['message' => $msg]);
        } else {
            $this->dispatch('item-saved', ['message' => "$itemsAdded items imported!"]);
        }
    }

    public function downloadSample()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=sample_items.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Category', 'Unit', 'Opening Stock']);
            fputcsv($file, ['Potato', 'Vegetables', 'KG', '50']);
            fputcsv($file, ['Milk', 'Dairy', 'Liter', '20']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function addBulkRow()
    {
        $this->bulkItems[] = ['name' => '', 'category_id' => '', 'unit_id' => '', 'opening_stock' => 0];
    }

    public function removeBulkRow($index)
    {
        unset($this->bulkItems[$index]);
        $this->bulkItems = array_values($this->bulkItems);
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $this->editingId = $id;
        $this->name = $item->name;
        $this->category_id = $item->category_id;
        $this->unit_id = $item->unit_id;
        $this->opening_stock = $item->opening_stock;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['name'] = 'required|string|max:255|unique:items,name,' . $this->editingId;
        }

        $this->validate($rules);

        Item::updateOrCreate(['id' => $this->editingId], [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'unit_id' => $this->unit_id,
            'opening_stock' => $this->opening_stock,
            'current_stock' => $this->editingId ? Item::find($this->editingId)->current_stock : $this->opening_stock,
        ]);

        $this->showModal = false;
        $this->dispatch('item-saved', ['message' => $this->editingId ? 'Item updated!' : 'Item created!']);
    }

    public function saveBulk()
    {
        $this->validate([
            'bulkItems.*.name' => 'required|string|max:255|unique:items,name',
            'bulkItems.*.category_id' => 'required|exists:categories,id',
            'bulkItems.*.unit_id' => 'required|exists:units,id',
            'bulkItems.*.opening_stock' => 'required|numeric|min:0',
        ], [
            'bulkItems.*.name.required' => 'Required',
            'bulkItems.*.name.unique' => 'Duplicate',
            'bulkItems.*.category_id.required' => 'Required',
            'bulkItems.*.unit_id.required' => 'Required',
        ]);

        foreach ($this->bulkItems as $itemData) {
            Item::create([
                'name' => $itemData['name'],
                'category_id' => $itemData['category_id'],
                'unit_id' => $itemData['unit_id'],
                'opening_stock' => $itemData['opening_stock'],
                'current_stock' => $itemData['opening_stock'],
            ]);
        }

        $this->showModal = false;
        $this->dispatch('item-saved', ['message' => count($this->bulkItems) . ' items added!']);
    }

    public function delete($id)
    {
        Item::findOrFail($id)->delete();
        $this->dispatch('item-deleted', ['message' => 'Item deleted!']);
    }

    public function render()
    {
        return view('admin.products.items.item-manager', [
            'items' => Item::with(['category', 'unit'])->latest()->get(),
            'categories' => Category::orderBy('name')->get(),
            'units' => Unit::orderBy('name')->get(),
        ]);
    }
}
