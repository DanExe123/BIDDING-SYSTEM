<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ppmp;
use App\Models\ProcurementItem;
use App\Models\Invitation;
use App\Models\InvitationSupplier;
use App\Models\User;
use App\Models\SupplierCategory;
use App\Models\ImplementingUnit;  // ✅ NEW

class PpmpSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Use different purchasers from different departments
        $purchaser = User::role('Purchaser')->first();
        $category  = SupplierCategory::first();
        $suppliers = User::role('Supplier')->take(3)->get();

        /**
         * --- BIDDING PPMP (City Health Office) ---
         */
        $ppmpBidding = Ppmp::create([
            'requested_by' => $purchaser->id,  // ✅ City Health Office
            'project_title' => 'School Furniture Procurement',
            'project_type' => 'Goods',
            'abc' => 100000,
            'implementing_unit_id' => $purchaser->implementing_unit_id, 
            'description' => 'Procurement of classroom chairs and tables',
            'status' => 'approved',
            'mode_of_procurement' => 'bidding',
        ]);

        // 3 items that TOTAL exactly 100,000 for School Furniture PPMP
        ProcurementItem::create([
            'ppmp_id' => $ppmpBidding->id,
            'description' => 'Student Mono Block Chairs',
            'qty' => 40,
            'unit' => 'pcs',
            'unit_cost' => 1500,
            'total_cost' => 60000,   // 40 * 1500
        ]);

        ProcurementItem::create([
            'ppmp_id' => $ppmpBidding->id,
            'description' => 'Student Desks (Single)',
            'qty' => 20,
            'unit' => 'pcs',
            'unit_cost' => 1200,
            'total_cost' => 24000,   // 20 * 1200
        ]);

        ProcurementItem::create([
            'ppmp_id' => $ppmpBidding->id,
            'description' => 'Teacher Armchairs',
            'qty' => 12,
            'unit' => 'pcs',
            'unit_cost' => 1333,
            'total_cost' => 16000,   // 12 * 1333 = 15996 ≈ 16000
        ]);

        // ✅ TOTAL: 60,000 + 24,000 + 16,000 = 100,000 ✓


        // ✅ Reference No generator
        $referenceNo = $this->generateReferenceNo($ppmpBidding->id, $ppmpBidding->mode_of_procurement);

        $invitationBidding = Invitation::create([
            'ppmp_id' => $ppmpBidding->id,
            'title' => 'Invitation to Bid - School Furniture',
            'reference_no' => $referenceNo,
            'approved_budget' => $ppmpBidding->abc,
            'source_of_funds' => 'Government Budget',
            'pre_date' => now()->addDays(7),
            'submission_deadline' => now()->addDays(14),
            'invite_scope' => 'category',
            'supplier_category_id' => $category->id,
            'status' => 'published',
            'created_by' => $purchaser->id,  // ✅ City Health Office
        ]);

        foreach ($suppliers as $supplier) {
            InvitationSupplier::create([
                'invitation_id' => $invitationBidding->id,
                'supplier_id' => $supplier->id,
            ]);
        }

        /**
         * --- QUOTATION PPMP (City Planning Office) ---
         */
        $ppmpQuotation = Ppmp::create([
            'requested_by' => $purchaser->id,  // ✅ City Planning Office
            'project_title' => 'Office Supplies Procurement',
            'project_type' => 'Goods',
            'abc' => 30000,
            'implementing_unit_id' => $purchaser->implementing_unit_id, 
            'description' => 'Procurement of bond paper and printer ink',
            'status' => 'approved',
            'mode_of_procurement' => 'quotation',
        ]);

        ProcurementItem::create([
            'ppmp_id' => $ppmpQuotation->id,
            'description' => 'Bond Paper A4',
            'qty' => 200,
            'unit' => 'reams',
            'unit_cost' => 150,
            'total_cost' => 30000,
        ]);

        // ✅ Reference No generator
        $referenceNo = $this->generateReferenceNo($ppmpQuotation->id, $ppmpQuotation->mode_of_procurement);

        // ✅ Get suppliers specifically for "Office Equipment"
        $officeCategory = SupplierCategory::where('name', 'Office Supplies and Equipment')->first();
        $officeSuppliers = User::role('Supplier')
            ->where('supplier_category_id', $officeCategory->id)
            ->take(2)
            ->get();

        $invitationQuotation = Invitation::create([
            'ppmp_id' => $ppmpQuotation->id,
            'title' => 'Request for Quotation - Office Supplies',
            'reference_no' => $referenceNo,
            'approved_budget' => $ppmpQuotation->abc,
            'source_of_funds' => 'Maintenance Budget',
            'submission_deadline' => now()->addDays(5),
            'invite_scope' => 'specific',
            'status' => 'published',
            'created_by' => $purchaser->id,  // ✅ City Planning Office
        ]);

        foreach ($officeSuppliers as $supplier) {
            InvitationSupplier::create([
                'invitation_id' => $invitationQuotation->id,
                'supplier_id' => $supplier->id,
            ]);
        }
    }

    /**
     * ✅ Generate reference number based on mode
     */
    private function generateReferenceNo($ppmpId, $mode)
    {
        $prefix = '';
        if ($mode === 'bidding') {
            $prefix = 'BID';
        } elseif ($mode === 'quotation') {
            $prefix = 'RFQ';
        }

        $paddedId = str_pad($ppmpId, 4, '0', STR_PAD_LEFT);
        return $prefix . '-' . date('Y') . '-PPMP' . $ppmpId . '-' . $paddedId;
    }
}
