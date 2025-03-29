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
            $table->string('company_key')->unique()->nullable()->after('id');  // Add company_key after 'id'
            $table->unsignedBigInteger('plan_id')->after('address');  // Add plan_id after 'address'// Set up foreign key constraint

            $table->decimal('plan_amount', 10, 2)->default(0)->after('registration_number');  // Add plan_amount after 'registration_number'
            $table->decimal('discount', 10, 2)->default(0)->after('plan_amount');  // Add discount after 'plan_amount'
            $table->decimal('final_price', 10, 2)->default(0)->after('discount');  // Add final_price after 'discount'
            $table->enum('status', ['active', 'expired'])->default('expired')->after('final_price');  // Add status after 'final_price'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Drop foreign key and columns during rollback
            $table->dropColumn('company_key');
            $table->dropColumn('plan_id');
            $table->dropColumn('plan_amount');
            $table->dropColumn('discount');
            $table->dropColumn('final_price');
            $table->dropColumn('status');
        });
    }
};
