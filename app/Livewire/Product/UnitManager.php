<?php

namespace App\Livewire\Product;

use App\Models\Unit;
use Livewire\Component;

class UnitManager extends Component
{
    public $name;
    public $fullname;
    public $showModal = false;
    public $editingId = null;

    protected $rules = [
        'name' => 'required|string|max:10|unique:units,name',
        'fullname' => 'nullable|string|max:255',
    ];

    public function create()
    {
        $this->reset(['name', 'fullname', 'editingId']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $this->editingId = $id;
        $this->name = $unit->name;
        $this->fullname = $unit->fullname;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['name'] = 'required|string|max:10|unique:units,name,' . $this->editingId;
        }

        $this->validate($rules);

        Unit::updateOrCreate(['id' => $this->editingId], [
            'name' => strtoupper($this->name),
            'fullname' => $this->fullname,
        ]);

        $this->showModal = false;
        $this->dispatch('unit-saved', ['message' => $this->editingId ? 'Unit updated!' : 'Unit created!']);
    }

    public function delete($id)
    {
        Unit::findOrFail($id)->delete();
        $this->dispatch('unit-deleted', ['message' => 'Unit deleted!']);
    }

    public function render()
    {
        return view('admin.products.units.unit-manager', [
            'units' => Unit::all(),
        ]);
    }
}
