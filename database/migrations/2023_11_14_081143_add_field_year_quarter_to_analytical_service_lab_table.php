<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldYearQuarterToAnalyticalServiceLabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analytical_service_lab', function (Blueprint $table) {
            $table->integer("year")->nullable();
            $table->integer("quarter")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analytical_service_lab', function (Blueprint $table) {
            $table->dropColumn("year");
            $table->dropColumn("quarter");
        });
    }
}
