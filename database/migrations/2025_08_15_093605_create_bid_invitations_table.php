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
        Schema::create('bid_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppmp_id')->constrained('ppmps')->cascadeOnDelete();
            $table->string('bid_title');
            $table->string('bid_reference')->unique();
            $table->decimal('approved_budget', 15, 2)->default(0);
            $table->string('source_of_funds')->nullable();
            $table->date('pre_bid_date')->nullable();
            $table->date('submission_deadline')->nullable();
            $table->json('bid_documents')->nullable(); // store list/metadata as JSON
            $table->string('invite_scope'); // all | category | specific
            $table->unsignedBigInteger('supplier_category_id')->nullable(); // for category scope
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
        Schema::dropIfExists('bid_invitations');
    }
};
