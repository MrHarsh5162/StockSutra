<?php

namespace App\Livewire\Product;

use App\Models\Category;
use Livewire\Component;

class CategoryManager extends Component
{
    public $name;
    public $showModal = false;
    public $editingId = null;

    protected $rules = [
        'name' => 'required|string|max:255|unique:categories,name',
    ];

    public function create()
    {
        $this->reset(['name', 'editingId']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->editingId = $id;
        $this->name = $category->name;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['name'] = 'required|string|max:255|unique:categories,name,' . $this->editingId;
        }

        $this->validate($rules);

        Category::updateOrCreate(['id' => $this->editingId], [
            'name' => $this->name,
        ]);

        $this->showModal = false;
        $this->dispatch('category-saved', ['message' => $this->editingId ? 'Category updated!' : 'Category created!']);
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        $this->dispatch('category-deleted', ['message' => 'Category deleted!']);
    }

    public function render()
    {
        return view('admin.products.categories.category-manager', [
            'categories' => Category::latest()->get(),
        ]);
    }
}
