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
       Schema::create('cars', function (Blueprint $table) {
    $table->id();
    $table->string('brand');
    $table->string('model');
    $table->string('type');
    $table->decimal('rate', 2, 1)->default(0);
    $table->integer('year');
    $table->decimal('price', 10, 2);
    $table->string('location');
    $table->integer('seats')->nullable();            // جديد
    $table->integer('large_bag')->nullable();        // جديد
    $table->integer('small_bag')->nullable();        // جديد
    $table->text('car_specification')->nullable();   // جديد
    $table->string('supplier')->nullable();          // جديد
    $table->text('description_en')->nullable();
    $table->text('description_ar')->nullable();
    $table->string('booking_link')->nullable();
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
