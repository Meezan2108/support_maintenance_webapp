<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalProjectCostDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_project_cost_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_project_cost_id')->index('proposal_project_cost_id_index');
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
        Schema::dropIfExists('proposal_project_cost_detail');
    }
}
