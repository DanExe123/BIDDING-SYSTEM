<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ppmp;
use App\Models\Invitation;
use App\Models\User;
use App\Models\SupplierCategory;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LogActivity;
use Livewire\WithFileUploads; 

class BacInvitation extends Component
{
    use WithPagination, WithFileUploads;

    public $selectedPpmp = null;

    // form fields
    public $title, $referenceNo, $approvedBudget, $sourceOfFunds;
    public $preDate, $submissionDeadline;
    public $documents = [];

    // supplier invite scope
    public $inviteScope = null; // all | category | specific
    public $supplierCategoryId = null;
    public $selectedSuppliers = [];

    public $categories = [];
    public $suppliers = [];
        // ✅ Change property name at top of class:
public $searchInv = '';  // Changed from $search
    public $supplierSearch = '';
     public $filterSupplierCategoryId = null; // already bound in Blade

    protected $paginationTheme = 'tailwind'; 

    public function removeDocument($index)
    {
        unset($this->documents[$index]);
        $this->documents = array_values($this->documents); // reindex array
    }

    
    public function showPpmp($ppmpId)
    {
        $this->selectedPpmp = Ppmp::find($ppmpId);

        $this->title = $this->selectedPpmp->project_title;
        $this->approvedBudget = $this->selectedPpmp->abc;
        $this->sourceOfFunds = $this->selectedPpmp->implementing_unit;


         $projectType = $this->selectedPpmp->project_type; // "Construction", "Goods", etc.

        // filter categories by project_type
        $this->categories = SupplierCategory::where('project_type', $projectType)->get();

        // filter suppliers by category.project_type
        $this->suppliers = User::role('supplier')
            ->where('account_status', 'verified')
            ->whereHas('supplierCategory', function ($q) use ($projectType) {
                $q->where('project_type', $projectType);
            })
            ->with('supplierCategory')
            ->get();

        // check mode_of_procurement
        $mode = $this->selectedPpmp->mode_of_procurement;
        $prefix = '';

        if ($mode === 'bidding') {
            $prefix = 'BID';
        } elseif ($mode === 'quotation') {
            $prefix = 'RFQ';
        }

        $paddedId = str_pad($ppmpId, 4, '0', STR_PAD_LEFT);
        $this->referenceNo = $prefix . '-' . date('Y') . '-PPMP' . $ppmpId . '-' . $paddedId;

        //dd($ppmpId);
    }

    public function closeModal()
    {
        $this->reset(['selectedPpmp']);
    } 

    protected function rules()
    {
        return [
            'selectedPpmp'        => 'required',
            'title'               => 'required|string|max:255',
            'referenceNo'         => 'required|string|max:255|unique:invitations,reference_no',
            'approvedBudget'      => 'required|numeric|min:0',
            'sourceOfFunds'       => 'required|string|max:255',
            'preDate'             => 'required|date',
            'submissionDeadline'  => 'required|date|after_or_equal:preDate',
            'inviteScope'         => 'required|in:category,specific',
            'supplierCategoryId'  => 'required_if:inviteScope,category|nullable|exists:supplier_categories,id',
            'selectedSuppliers' => 'required_if:inviteScope,specific|array',
            'selectedSuppliers.*' => 'exists:users,id',
           // 'documents.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }

    public function saveInvitation()
    {
        $this->validate();

        $paths = [];
        $names = []; // <-- this is your array

       /*  if ($this->documents) {
            foreach ($this->documents as $file) {
                $paths[] = $file->store('invitations', 'public');       // save file path
                $names[] = $file->getClientOriginalName();              // save original name
            }
        }*/

        $invitation = Invitation::create([
            'ppmp_id'             => $this->selectedPpmp->id,
            'title'               => $this->title,
            'reference_no'        => $this->referenceNo,
            'approved_budget'     => $this->approvedBudget,
            'source_of_funds'     => $this->sourceOfFunds,
            'pre_date'            => $this->preDate,
            'submission_deadline' => $this->submissionDeadline,
          //  'documents'           => json_encode($paths), // store paths array
          //  'document_names'        => json_encode($names), // <-- use $names here
            'invite_scope'        => $this->inviteScope,
            'supplier_category_id'=> $this->supplierCategoryId,
            'created_by'          => Auth::id(),
            'status'              => 'published',
        ]);


        switch ($this->inviteScope) {
            case 'category':
                $suppliers = User::role('supplier')
                    ->where('supplier_category_id', $this->supplierCategoryId)
                    ->where('account_status', 'verified')
                    ->pluck('id');
                break;
            case 'specific':
                $suppliers = $this->selectedSuppliers ?? [];
                break;
            default:
                $suppliers = [];
        }

        $invitation->suppliers()->sync($suppliers);

        //  LOG ACTIVITY
        LogActivity::add(
            "created an Invitation for procurement - Reference No:: {$invitation->reference_no}"
        );


        session()->flash('message', 'Invitation Published.');
        $this->closeModal();
        $this->dispatch('close-modal');
    }
    
    public function mount()
    {
        // empty; categories/suppliers are loaded when PPMP is selected
        $this->categories = collect();
        $this->suppliers  = collect();
    }

    public function searchSuppliers()
    {
        if (!$this->selectedPpmp) {
            $this->suppliers = collect();
            return;
        }

        $projectType = $this->selectedPpmp->project_type;

        $this->suppliers = User::role('supplier')
            ->where('account_status', 'verified')
            ->whereHas('supplierCategory', function ($q) use ($projectType) {
                $q->where('project_type', $projectType);
            })
            ->with('supplierCategory')
            ->when($this->supplierSearch, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->supplierSearch . '%')
                    ->orWhere('last_name', 'like', '%' . $this->supplierSearch . '%');
                });
            })
            ->when($this->filterSupplierCategoryId, function ($q) {
                $q->where('supplier_category_id', $this->filterSupplierCategoryId);
            })
            ->get();
    }
 

    // ✅ Change method name:
    public function updatedSearchInv()  // Changed from updatedSearch
    {
        $this->resetPage();
    }

    // ✅ Replace your render() method:
    public function render()
    {
        $query = Ppmp::with(['items', 'invitations'])
            ->where('status', 'approved')
            ->whereNotNull('mode_of_procurement')
            ->where('mode_of_procurement', '!=', '');

        // ✅ ROLE-BASED FILTERING
        $this->isPurchaser = auth()->user()->hasRole('Purchaser');
        if ($this->isPurchaser) {
            $query->where('requested_by', auth()->id());
        }

        // ✅ SEARCH by project_title OR reference_no using searchInv
        $query->when($this->searchInv, function($q) {
            $q->where(function($sub) {
                $sub->where('project_title', 'like', '%'.$this->searchInv.'%')
                    ->orWhereHas('invitations', function($inv) {
                        $inv->where('reference_no', 'like', '%'.$this->searchInv.'%');
                    });
            });
        });

        return view('livewire.bac-invitation', [
            'ppmps' => $query->orderBy('created_at', 'desc')->paginate(10),
            'isPurchaser' => $this->isPurchaser,
        ]);
    }

    
}
