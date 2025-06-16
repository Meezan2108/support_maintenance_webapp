<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProposalIdToReportResearchProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_research_progress', function (Blueprint $table) {
            $table->foreignId("proposal_id")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_research_progress', function (Blueprint $table) {
            $table->foreignId("proposal_id")->change();
        });
    }
}
