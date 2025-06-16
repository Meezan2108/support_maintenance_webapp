<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalProjectActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_project_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->index('proposal_id_index');
            $table->string('activities');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->integer('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_project_activities');
    }
}
