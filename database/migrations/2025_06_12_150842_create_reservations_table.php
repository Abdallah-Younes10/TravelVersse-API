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
       Schema::create('reservations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // الشخص اللي حجز
    $table->morphs('reservable'); // مرتبط بـ trip/hotel/etc.
    $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
    $table->boolean('is_paid')->default(false); // دفع وهمي
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
