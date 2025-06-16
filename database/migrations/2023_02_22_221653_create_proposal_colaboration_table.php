<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalColaborationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_collaboration', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->index('proposal_id_index');
            $table->integer('type'); // 1: Institution, 2: Industries
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('other')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_collaboration');
    }
}
