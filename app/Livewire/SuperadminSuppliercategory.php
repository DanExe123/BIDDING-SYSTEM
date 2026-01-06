<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SupplierCategory;

class SuperadminSuppliercategory extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $categoryId;
    public $name;
    public $project_type;
    public $isOpen = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255|unique:supplier_categories,name',
        'project_type' => 'required|string|max:255|unique:supplier_categories,project_type',
    ];

    public function render()
    {
        $categories = SupplierCategory::withCount('users')
            ->where('name', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.superadmin-suppliercategory', [
            'categories' => $categories
        ]);
    }

    public function openModal()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetInput()
    {
        $this->categoryId = null;
        $this->name = '';
    }

    public function store()
    {
        $this->validate();

        SupplierCategory::create([
            'name' => $this->name,
            'project_type' => $this->project_type,
        ]);

        session()->flash('message', 'Supplier Category Created.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $category = SupplierCategory::findOrFail($id);

        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->isOpen = true;

        $this->rules['name'] =
            'required|string|max:255|unique:supplier_categories,name,' . $id;
            'required|string|max:255|unique:supplier_categories,project_type,' . $id;
    }

    public function update()
    {
        $this->validate();

        SupplierCategory::find($this->categoryId)->update([
            'name' => $this->name
        ]);

        session()->flash('message', 'Supplier Category Updated.');
        $this->closeModal();
    }

    public function delete($id)
    {
        SupplierCategory::findOrFail($id)->delete();
        session()->flash('message', 'Supplier Category Deleted.');
    }
}
