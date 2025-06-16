<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalResearchFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_research_field', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id');
            $table->integer('type');
            $table->foreignId('ref_for_category_id')->index('ref_for_category_id_index');
            $table->foreignId('ref_for_group_id')->index('ref_for_group_id_index');
            $table->foreignId('ref_for_area_id')->index('ref_for_area_id_index');

            $table->index(['proposal_id', 'type'], 'research_field_type_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_research_field');
    }
}
