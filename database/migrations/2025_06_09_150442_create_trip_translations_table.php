<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('trip_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->string('locale', 5); // 'en', 'ar'...
            $table->string('title');
            $table->text('description');
            $table->timestamps();

            $table->unique(['trip_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_translations');
    }
}
