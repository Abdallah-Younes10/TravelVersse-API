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
    Schema::create('activities', function (Blueprint $table) {
        $table->id();
        $table->string('name_en');
        $table->string('name_ar');
        $table->text('description_en')->nullable();
        $table->text('description_ar')->nullable();
        $table->decimal('rate', 2, 1)->default(0);
        $table->string('location');
        $table->decimal('price', 10, 2);
        $table->string('booking_link')->nullable();
        $table->string('duration')->nullable();
        $table->string('type')->nullable();

        // ✅ الحقول الجديدة
        $table->json('start_time')->nullable();
        $table->boolean('live_guide')->default(false);
        $table->json('guide_languages')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
