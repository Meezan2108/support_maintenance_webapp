<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalProjectCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('proposal_project_cost', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->index('proposal_id_index');
            $table->foreignId('ref_project_cost_series_id')->index('ref_project_cost_series_id_index');
            $table->text('description');
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
        Schema::dropIfExists('proposal_project_cost');
    }
}
