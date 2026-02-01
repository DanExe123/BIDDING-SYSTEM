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
        Schema::table('submissions', function (Blueprint $table) {
            $table->decimal('weighted_technical_score', 5, 2)
                  ->nullable()
                  ->after('tech_sustainability'); // <-- right after tech_sustainability
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('weighted_technical_score');
        });
    }
};
