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
       Schema::create('hotels', function (Blueprint $table) {
    $table->id();
    $table->string('name_en');
    $table->string('name_ar')->nullable();
    $table->text('description_en')->nullable();
    $table->text('description_ar')->nullable();
    $table->decimal('rate', 2, 1)->default(0);
    $table->decimal('price', 10, 2)->nullable();
    $table->decimal('old_price', 10, 2)->nullable(); // جديد
    $table->string('class')->nullable();             // جديد: مثل "5 stars"
    $table->string('style')->nullable();             // جديد: مثل "luxury"
    $table->json('amenities')->nullable();           // جديد: array
    $table->string('location')->nullable();
    $table->string('booking_link')->nullable();
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
