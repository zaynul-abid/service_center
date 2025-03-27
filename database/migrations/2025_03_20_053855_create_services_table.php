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
        Schema::create('services', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('booking_id')->unique(); // Unique booking ID
            $table->string('reference_number')->nullable(); // Reference number (optional)
            $table->date('booking_date'); // Booking date
            $table->time('booking_time'); // Booking time
            $table->string('vehicle_number'); // Vehicle number
            $table->string('vehicle_type');
            $table->string('vehicle_company'); // Vehicle company
            $table->string('vehicle_model'); // Vehicle model
            $table->string('fuel_type'); // Fuel Type (Petrol, Diesel, Electric, etc.)
            $table->string('fuel_level')->nullable(); // Fuel Level (Full, Half, Quarter, etc.)
            $table->integer('km_driven')->nullable(); // Kilometers Driven
            $table->string('customer_name'); // Customer name
            $table->string('place')->nullable(); // Place
            $table->string('contact_number_1'); // Contact number 1
            $table->string('contact_number_2')->nullable(); // Contact number 2 (optional)
            $table->text('service_details')->nullable(); // Service details
            $table->text('customer_complaint'); // Customer complaint
            $table->text('remarks')->nullable(); // Remarks (optional)
            $table->decimal('cost', 10, 2)->nullable(); // Cost (decimal with 10 digits total and 2 decimal places)
            $table->date('expected_delivery_date')->nullable(); // Expected delivery date
            $table->time('expected_delivery_time')->nullable(); // Expected delivery time
            $table->unsignedBigInteger('company_id'); // Company ID (Service Center)
            $table->json('photos')->nullable(); // Multiple photos (stored as JSON)
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade'); // Foreign key constraint
            $table->softDeletes(); // Soft delete feature
            $table->string('dummy_column_1')->nullable(); // Dummy column 1
            $table->string('dummy_column_2')->nullable(); // Dummy column 2
            $table->integer('dummy_column_3')->nullable(); // Dummy column 3
            $table->boolean('dummy_column_4')->default(false); // Dummy column 4 (boolean)
            $table->text('dummy_column_5')->nullable(); // Dummy column 5
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
