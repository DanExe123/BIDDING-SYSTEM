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
        Schema::create('implementing_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // ✅ EXACTLY like supplier_categories migration
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('implementing_unit_id')
                ->nullable() // only required if role = Purchaser
                ->constrained('implementing_units')
                ->nullOnDelete()
                ->after('supplier_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('implementing_units');
    }
};
