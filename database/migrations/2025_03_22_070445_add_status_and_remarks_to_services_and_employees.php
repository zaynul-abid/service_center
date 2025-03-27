<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('assigned_service_status')->default('Available')->after('updated_at');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('service_status')->default('Pending')->change(); // change if it exists
            $table->text('employee_remarks')->nullable()->after('service_status');
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('assigned_service_status');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('employee_remarks');
        });
    }
};
