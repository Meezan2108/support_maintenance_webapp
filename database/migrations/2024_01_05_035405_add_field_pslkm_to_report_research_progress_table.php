<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldPslkmToReportResearchProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_research_progress', function (Blueprint $table) {
            $table->foreignId("ref_pslkm_id")->after("programm")->nullable();
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
            $table->dropColumn("ref_pslkm_id");
        });
    }
}
