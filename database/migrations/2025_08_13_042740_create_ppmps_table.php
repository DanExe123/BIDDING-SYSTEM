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
        Schema::create('ppmps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->string('project_title');
            $table->string('project_type');
            $table->decimal('abc', 15, 2); // Approved Budget
            $table->string('implementing_unit');
            $table->text('description');
            $table->string('attachment')->nullable();
            $table->string('attachment_name')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('mode_of_procurement', ['quotation', 'bidding'])->nullable()->default(null);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppmps');
    }
};
