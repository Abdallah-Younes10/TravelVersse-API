<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    public function up()
{
    Schema::create('trips', function (Blueprint $table) {
        $table->id();
        $table->string('name_en');
        $table->string('name_ar');
        $table->text('description_en')->nullable();
        $table->text('description_ar')->nullable();
        $table->string('location');
        $table->decimal('price', 10, 2);

        $table->unsignedBigInteger('guide_id');
        $table->foreign('guide_id')->references('id')->on('users')->onDelete('cascade')->nullable()->change();;

        $table->decimal('rate', 2, 1)->default(0);

        // الجديد:
        $table->string('duration')->nullable();       // مدة الرحلة
        $table->string('continent')->nullable();      // القارة
        $table->enum('difficulty', ['easy', 'moderate', 'hard'])->nullable();
 // الصعوبة

        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('trips');
    }
}
