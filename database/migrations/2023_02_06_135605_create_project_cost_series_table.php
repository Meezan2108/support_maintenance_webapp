<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectCostSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_project_cost_series', function (Blueprint $table) {
            $table->id();
            $table->string('act_code')->unique("act_code_unique");
            $table->string('jseries_code')->unique("jseries_code_unique");
            $table->string('vseries_code')->unique("vseries_code_unique");
            $table->string('order');
            $table->string('description');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_project_cost_series');
    }
}
