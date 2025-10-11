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
    public $attachment;
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
            'attachment' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        // 🚨 Prevent over-budget
        if ($this->totalBudget > 150000) {
            $this->addError('abc', 'Approved Budget must not exceed ₱150,000.');
            return; // stop saving
        }

        // Handle attachment upload
        $storedPath = $this->attachment
            ? $this->attachment->store('attachments', 'public')
            : null;

        $originalName = $this->attachment
            ? $this->attachment->getClientOriginalName()
            : null;

        $ppmp = Ppmp::create([
            'project_title' => $this->project_title,
            'project_type' => $this->project_type,
            'abc' => $this->abc, // already synced with totalBudget
            'implementing_unit' => $this->implementing_unit,
            'description' => $this->description,
            'attachment' => $storedPath,
            'attachment_name' => $originalName,
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
        $this->reset(); // Clear form
    }

    
   public function render()
    {
        return view('livewire.purchaser-procurement-planning', [
            'ppmps' => Ppmp::with('items')   // 👈 eager load items
                ->where('requested_by', Auth::id())
                ->latest()
                ->get()
        ]);
    }

   
}