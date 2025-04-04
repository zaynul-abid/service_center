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
        Schema::table('companies', function (Blueprint $table) {
            // 1. First drop the foreign key constraint
            $table->dropForeign(['original_company_id']);

            // 2. Then drop the column
            $table->dropColumn('original_company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedBigInteger('original_company_id')->nullable()->after('id');
            $table->foreign('original_company_id')->references('id')->on('companies');
        });
    }
};
