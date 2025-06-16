<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldProjectInvolvedAtOutputRndTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('output_rnd', function (Blueprint $table) {
            $table->text("project_involved")->nullable()
                ->after("date_output");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('output_rnd', function (Blueprint $table) {
            $table->dropColumn("project_involved");
        });
    }
}
