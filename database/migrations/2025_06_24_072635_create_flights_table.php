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
        Schema::create('flights', function (Blueprint $table) {
    $table->id();
    $table->string('style')->nullable(); // اقتصادي - بيزنس - فاخر
    $table->string('from_location');
    $table->dateTime('departure_time');
    $table->string('to_location');
    $table->dateTime('arrival_time');

    $table->string('return_from')->nullable();
    $table->dateTime('return_departure_time')->nullable();
    $table->string('return_to')->nullable();
    $table->dateTime('return_arrival_time')->nullable();

    $table->integer('stops_count')->default(0);
    $table->json('stop_locations')->nullable(); // Array من أسماء المدن أو المطارات

    $table->decimal('duration', 5, 2); // بالساعات
    $table->decimal('price', 10, 2);
    $table->decimal('rate', 2, 1)->default(0);
    $table->string('booking_link')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
