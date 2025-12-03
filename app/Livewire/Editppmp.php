<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Ppmp;
use App\Models\ProcurementItem;
use Illuminate\Support\Facades\Auth;

class Editppmp extends Component
{
    use WithFileUploads;

    public $ppmpId;
    public $project_title;
    public $project_type;
    public $abc;
    public $implementing_unit;
    public $description;
    public $attachments = [];
    public $existingAttachments = [];
    public $existingAttachmentNames = [];
    
    public $items = [];

    public function mount($id)
    {
        $this->ppmpId = $id;
        $ppmp = Ppmp::with('items')->findOrFail($id);

        $this->project_title = $ppmp->project_title;
        $this->project_type = $ppmp->project_type;
        $this->abc = $ppmp->abc;
        $this->implementing_unit = $ppmp->implementing_unit;
        $this->description = $ppmp->description;
        $this->existingAttachments = $ppmp->attachments ?? [];
        $this->existingAttachmentNames = is_array($ppmp->attachment_names) 
        ? $ppmp->attachment_names 
        : json_decode($ppmp->attachment_names, true) ?? [];
        $this->items = $ppmp->items->map(function($item) {
            return [
                'id' => $item->id,
                'description' => $item->description,
                'qty' => $item->qty,
                'unit' => $item->unit,
                'unitCost' => $item->unit_cost
            ];
        })->toArray();
    }

    public function addItem()
    {
        $this->items[] = [
            'id' => null,
            'description' => '',
            'qty' => 0,
            'unit' => '',
            'unitCost' => 0
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function removeAttachment($index)
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
    }

    public function removeExistingAttachment($index)
{
    // Remove the path and the name
    if(isset($this->existingAttachments[$index])){
        unset($this->existingAttachments[$index]);
        $this->existingAttachments = array_values($this->existingAttachments);
    }
    if(isset($this->existingAttachmentNames[$index])){
        unset($this->existingAttachmentNames[$index]);
        $this->existingAttachmentNames = array_values($this->existingAttachmentNames);
    }
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
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $ppmp = Ppmp::findOrFail($this->ppmpId);
        $ppmp->update([
            'project_title' => $this->project_title,
            'project_type' => $this->project_type,
            'abc' => $this->abc,
            'implementing_unit' => $this->implementing_unit,
            'description' => $this->description
        ]);

        // Save attachments
        foreach ($this->attachments as $file) {
            $ppmp->attachments = array_merge($ppmp->attachments ?? [], [$file->store('attachments', 'public')]);
        }
        $ppmp->save();

        // Save/update items
        foreach ($this->items as $item) {
            if(isset($item['id']) && $item['id']){
                $existing = ProcurementItem::find($item['id']);
                if($existing){
                    $existing->update([
                        'description' => $item['description'],
                        'qty' => $item['qty'],
                        'unit' => $item['unit'],
                        'unit_cost' => $item['unitCost'],
                        'total_cost' => $item['qty'] * $item['unitCost'],
                    ]);
                }
            } else {
                $ppmp->items()->create([
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit' => $item['unit'],
                    'unit_cost' => $item['unitCost'],
                    'total_cost' => $item['qty'] * $item['unitCost'],
                ]);
            }
        }

        session()->flash('message', 'PPMP updated successfully.');
        $this->emit('ppmpUpdated'); // optional event to refresh parent
    }

    public function render()
    {
        return view('livewire.editppmp');
    }
}
