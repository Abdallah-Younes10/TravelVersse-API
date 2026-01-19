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
        Schema::create('cruises', function (Blueprint $table) {
    $table->id();
    $table->string('name_en');
    $table->string('name_ar');
    $table->text('description_en')->nullable();
    $table->text('description_ar')->nullable();
    $table->decimal('rate', 2, 1)->default(0);
    $table->string('location');
    $table->decimal('price', 10, 2);
    $table->string('booking_link')->nullable();
    $table->string('from')->nullable(); // نقطة الانطلاق
$table->string('to')->nullable();   // نقطة الوصول
$table->time('depart_time')->nullable();
$table->time('return_time')->nullable(); // ممكن مفيش عودة
$table->string('property_type')->nullable();
$table->string('style')->nullable();
$table->text('amenities')->nullable(); // ممكن تخزن array كـ JSON

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cruises');
    }
};
