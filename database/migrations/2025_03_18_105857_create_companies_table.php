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
        Schema::create('companies', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('company_name');
            $table->string('contact_number');
            $table->text('address');
            $table->string('registration_number');
            $table->string('plan');
            $table->date('subscription_start_date');
            $table->date('subscription_end_date');

            $table->string('reserve_1')->nullable();
            $table->string('reserve_2')->nullable();
            $table->string('reserve_3')->nullable();
            $table->string('reserve_4')->nullable();
            $table->string('reserve_5')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
