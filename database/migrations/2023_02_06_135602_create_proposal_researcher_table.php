<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalResearcherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_researcher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->index('proposal_id_index');
            $table->string('name');
            $table->string('nric')->nullable();
            $table->foreignId('ref_division_id')->nullable();
            $table->foreignId('ref_position_id')->nullable();
            $table->string('tel_no', 64);
            $table->string('fax_no', 64);
            $table->string('email', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_researcher');
    }
}
