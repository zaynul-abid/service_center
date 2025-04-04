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
            DB::statement('ALTER TABLE companies MODIFY plan_id BIGINT UNSIGNED NULL');

            // Add foreign key constraint
            $table->foreign('plan_id')
                ->references('id')
                ->on('plans')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            // Change back to NOT NULL if needed
            DB::statement('ALTER TABLE companies MODIFY plan_id BIGINT UNSIGNED NOT NULL');
        });
    }
};
