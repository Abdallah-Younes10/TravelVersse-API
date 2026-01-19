<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('trips', function (Blueprint $table) {
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
          $table->string('booking_link')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('trips', function (Blueprint $table) {
        $table->dropForeign(['guide_id']);
        $table->dropColumn('guide_id');
         $table->dropColumn('booking_url');
    });
}
};
