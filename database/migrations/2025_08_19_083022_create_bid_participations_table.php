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
        Schema::create('bid_participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bid_invitation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // supplier
            $table->decimal('bid_amount', 15, 2);
            $table->text('notes')->nullable();
            $table->string('technical_proposal_path')->nullable();
            $table->string('financial_proposal_path')->nullable();
            $table->string('company_profile_path')->nullable();
            $table->boolean('is_certified')->default(false);
            $table->enum('status', ['submitted','under_review','awarded','rejected'])->default('submitted');
            $table->timestamps();

            $table->unique(['bid_invitation_id', 'user_id']); // one submission per supplier per bid
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bid_participations');
    }
};
