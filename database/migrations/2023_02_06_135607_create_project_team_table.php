<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_project_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->index('proposal_id_index');
            $table->integer('type')->index('type_index');
            $table->string('position')->nullable();
            $table->string('name');
            $table->string('organization')->nullable();
            $table->string('man_month')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_project_team');
    }
}
