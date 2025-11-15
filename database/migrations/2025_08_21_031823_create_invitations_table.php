<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            // link to PPMP request
            $table->foreignId('ppmp_id')->constrained('ppmps')->cascadeOnDelete();
            $table->string('title');
            $table->string('reference_no')->unique();
            $table->decimal('approved_budget', 15, 2)->default(0);
            $table->string('source_of_funds')->nullable();
            $table->date('pre_date')->nullable();
            $table->date('submission_deadline')->nullable();
            $table->longText('documents')->nullable();      // store JSON array of file paths
            $table->longText('document_names')->nullable();
            $table->string('invite_scope'); // all | category | specific
            $table->unsignedBigInteger('supplier_category_id')->nullable();
            //  $table->enum('procurement_type', ['bidding', 'quotation']); // new: type of procurement
            // status flow
            $table->string('status')->default('draft'); // draft|published|closed|awarded (optional but handy)
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
