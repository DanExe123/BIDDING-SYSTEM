<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('technical_proposal_original_name')->nullable()->after('technical_proposal_path');
            $table->string('financial_proposal_original_name')->nullable()->after('financial_proposal_path');
            $table->string('company_profile_original_name')->nullable()->after('company_profile_path');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn([
                'technical_proposal_original_name',
                'financial_proposal_original_name',
                'company_profile_original_name',
            ]);
        });
    }
};
