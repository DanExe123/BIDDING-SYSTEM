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
        Schema::create('awarded_items', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('ppmp_id')->constrained()->cascadeOnDelete();
            $table->foreignId('procurement_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('users');

            $table->string('sku')->unique(); // generated SKU
            $table->string('description');
            $table->integer('qty');
            $table->string('unit');
            $table->decimal('unit_cost', 12, 2);
            $table->decimal('total_cost', 12, 2);
            $table->enum('status', ['received', 'not_received'])->default('not_received');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awarded_items');
    }
};
