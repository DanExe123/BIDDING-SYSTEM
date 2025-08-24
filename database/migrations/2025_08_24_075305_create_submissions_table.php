<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('users')->cascadeOnDelete();

            // Bidding-specific fields (nullable; used only when PPMP.mode_of_procurement === 'bidding')
            $table->decimal('bid_amount', 15, 2)->nullable();
            $table->string('technical_proposal_path')->nullable();
            $table->string('financial_proposal_path')->nullable();
            $table->string('company_profile_path')->nullable();
            $table->boolean('is_certified')->default(false);

            // Common
            $table->text('remarks')->nullable();
            $table->enum('status', ['draft','submitted','under_review','awarded','rejected'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['invitation_id', 'supplier_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
