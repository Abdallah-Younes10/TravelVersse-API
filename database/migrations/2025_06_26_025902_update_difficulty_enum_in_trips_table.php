<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDifficultyEnumInTripsTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE trips MODIFY difficulty ENUM('easy', 'medium', 'hard') NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE trips MODIFY difficulty ENUM('easy', 'hard') NULL");
    }
}
