<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefEvaluationRecFundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_evaluation_rec_fund', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->index('evaluation_id_index');
            $table->foreignId('ref_project_cost_series_id')->index('ref_project_cost_series_id_index');
            $table->integer('year');
            $table->bigInteger('cost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_evaluation_rec_fund');
    }
}
