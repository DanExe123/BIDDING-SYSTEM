<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Ppmp;
use App\Models\ProcurementItem;
use Illuminate\Support\Facades\Auth;

class PurchaserProcurementPlanning extends Component
{
    use WithFileUploads;

    public $project_title;
    public $project_type;
    public $abc;
    public $implementing_unit;
    public $description;
    public $attachments = []; // array for multiple files

    public $items = [];

    public function mount()
    {
        // Start with one empty item row
        $this->items[] = [
            'description' => '',
            'qty' => 0,
            'unit' => '',
            'unitCost' => 0,
            'delivery' => ''
        ];
    }

    public function addItem()
    {
        $this->items[] = [
            'description' => '',
            'qty' => 0,
            'unit' => '',
            'unitCost' => 0,
            'delivery' => ''
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // reindex
    }

    public function getTotalBudgetProperty()
    {
        return collect($this->items)->sum(function ($item) {
            return (float)($item['qty'] ?? 0) * (float)($item['unitCost'] ?? 0);
        });
    }

    // Keep abc in sync with totalBudget
    public function updatedItems()
    {
        $this->abc = $this->totalBudget;
    }

    public function removeAttachment($index)
    {
        // Remove the selected file
        unset($this->attachments[$index]);

        // Re-index the array so Livewire updates properly
        $this->attachments = array_values($this->attachments);
    }

    public function pota()
    {
        // Only validate first
        $validatedData = $this->validate([
            'project_title' => 'required|string|max:255',
            'project_type' => 'required|string|max:255',
            'abc' => 'required|numeric',
            'implementing_unit' => 'required|string|max:255',
            'description' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit' => 'required|string|max:50',
            'items.*.unitCost' => 'required|numeric|min:0',
            'attachments' => 'required|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        // Check total budget
        if ($this->totalBudget > 150000) {
            $this->addError('abc', 'Approved Budget must not exceed ₱150,000.');
            return;
        }

        // If everything passes, trigger Alpine modal to confirm
        $this->dispatch('show-ppmp-confirmation');
    }

    //submit request
    public function save()
    {
        $this->validate([
            'project_title' => 'required|string|max:255',
            'project_type' => 'required|string|max:255',
            'abc' => 'required|numeric',
            'implementing_unit' => 'required|string|max:255',
            'description' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit' => 'required|string|max:50',
            'items.*.unitCost' => 'required|numeric|min:0',
            'attachments' => 'required|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        //prevent over budget
        if ($this->totalBudget > 150000) {
            $this->addError('abc', 'Approved Budget must not exceed ₱150,000.');
            return;
        }

        $storedPaths = [];
        $originalNames = [];

        foreach ($this->attachments as $file) {
            $storedPaths[] = $file->store('attachments', 'public');
            $originalNames[] = $file->getClientOriginalName();
        }

        $ppmp = Ppmp::create([
            'project_title' => $this->project_title,
            'project_type' => $this->project_type,
            'abc' => $this->abc,
            'implementing_unit' => $this->implementing_unit,
            'description' => $this->description,
            'attachments' => $storedPaths,
            'attachment_names' => $originalNames,
            'status' => 'pending',
            'requested_by' => Auth::id(),
        ]);

        foreach ($this->items as $item) {
            $ppmp->items()->create([
                'description' => $item['description'],
                'qty' => $item['qty'],
                'unit' => $item['unit'],
                'unit_cost' => $item['unitCost'],
                'total_cost' => $item['qty'] * $item['unitCost'],
            ]);
        }

        session()->flash('message', 'PPMP created successfully.');

        $this->reset(['project_title','project_type','abc','implementing_unit','description','attachments','items']);
    }
    
   public function render()
    {
        return view('livewire.purchaser-procurement-planning', [
            'ppmps' => Ppmp::with('items')   // 👈 eager load items
                ->where('requested_by', Auth::id())
                ->orderBy('created_at', 'asc')
                ->get()
        ]);
    }

   
}