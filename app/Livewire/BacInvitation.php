<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ppmp;
use App\Models\Invitation;
use App\Models\User;
use App\Models\SupplierCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads; 

class BacInvitation extends Component
{
    use WithPagination, WithFileUploads;

    public $selectedPpmp = null;

    // form fields
    public $title, $referenceNo, $approvedBudget, $sourceOfFunds;
    public $preDate, $submissionDeadline, $documents;

    // supplier invite scope
    public $inviteScope = null; // all | category | specific
    public $supplierCategoryId = null;
    public $selectedSuppliers = [];

    public $categories = [];
    public $suppliers = [];
    public $supplierSearch = '';

    protected $paginationTheme = 'tailwind'; 

    public function mount()
    {
        // load categories and suppliers (not paginated)
        $this->categories = SupplierCategory::all();
        $this->suppliers = User::role('supplier')->with('supplierCategory')->get();
    }

    
    public function showPpmp($ppmpId)
    {
        $this->selectedPpmp = Ppmp::find($ppmpId);

        $this->title = $this->selectedPpmp->project_title;
        $this->approvedBudget = $this->selectedPpmp->abc;
        $this->sourceOfFunds = $this->selectedPpmp->implementing_unit;

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
            'selectedSuppliers'   => 'required_if:inviteScope,specific|array',
            'selectedSuppliers.*' => 'exists:users,id',
            'documents'           => 'nullable|file|max:2048',
        ];
    }

    public function saveInvitation()
    {
        $this->validate();

        $path = null;
        $originalName = null;
        if ($this->documents) {
            // save file
            $path = $this->documents->store('invitations', 'public');
            // get original name
            $originalName = $this->documents->getClientOriginalName();
        }

        $invitation = Invitation::create([
            'ppmp_id'             => $this->selectedPpmp->id,
            'title'               => $this->title,
            'reference_no'        => $this->referenceNo,
            'approved_budget'     => $this->approvedBudget,
            'source_of_funds'     => $this->sourceOfFunds,
            'pre_date'            => $this->preDate,
            'submission_deadline' => $this->submissionDeadline,
            'documents'           => $path,
            'document_name'       => $originalName,
            'invite_scope'        => $this->inviteScope,
            'supplier_category_id'=> $this->supplierCategoryId,
            'created_by'          => Auth::id(),
            'status'              => 'published',
        ]);


        switch ($this->inviteScope) {
            case 'category':
                $suppliers = User::role('supplier')
                    ->where('supplier_category_id', $this->supplierCategoryId)
                    ->pluck('id');
                break;
            case 'specific':
                $suppliers = $this->selectedSuppliers ?? [];
                break;
            default:
                $suppliers = [];
        }

        $invitation->suppliers()->sync($suppliers);

        session()->flash('message', 'Invitation Published.');
        $this->closeModal();
        $this->dispatch('close-modal');
    }
    
    //supplier search 
    public function searchSuppliers()
    {
        $this->suppliers = User::role('supplier')
            ->with('supplierCategory')
            ->when($this->supplierSearch, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->supplierSearch . '%')
                    ->orWhere('last_name', 'like', '%' . $this->supplierSearch . '%');
                });
            })
            ->get();
    }
    
    public function render()
    {
        return view('livewire.bac-invitation', [
            // ✅ paginate ppmps instead of loading all in mount
            'ppmps' => Ppmp::where('status', 'approved')
                ->whereNotNull('mode_of_procurement') // exclude null values
                ->where('mode_of_procurement', '!=', '') // exclude empty string
                ->with(['items', 'invitations']) 
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ]);
    }
    
}
