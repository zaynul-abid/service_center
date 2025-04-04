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
        Schema::create('subscription_histories', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_number');
            $table->text('address');
            $table->string('registration_number');
            $table->string('company_key');

            // Plan Details
            $table->string('plan_name');
            $table->decimal('plan_amount', 10, 2);
            $table->integer('plan_duration_days');

            // Subscription Details
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('final_amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->string('status')->default('active');

            // Metadata
            $table->boolean('is_renewal')->default(false);
            $table->timestamps();

            // Indexes
            $table->index('company_id');
            $table->index('plan_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_histories');
    }
};
