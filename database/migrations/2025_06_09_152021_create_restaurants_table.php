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
       Schema::create('restaurants', function (Blueprint $table) {
    $table->id();
    $table->string('name_en');
    $table->string('name_ar');
    $table->text('description_en')->nullable();
    $table->text('description_ar')->nullable();
    $table->decimal('rate', 2, 1)->default(0);
    $table->string('location');
    $table->string('booking_link')->nullable();

    // الحقول الجديدة:
    $table->string('property_type')->nullable(); // مثل: restaurant, cafe, kiosk
    $table->string('cuisine')->nullable();       // مثل: Italian, Asian
    $table->json('features')->nullable();        // مثل: ["delivery", "outdoor seating"]

    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
