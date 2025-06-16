<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldRefPslkmSubIdToReportResearchProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_research_progress', function (Blueprint $table) {
            $table->foreignId("ref_pslkm_sub_id")->nullable()->index("ref_pslkm_sub_id_index");
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
            $table->dropColumn("ref_pslkm_sub_id");
        });
    }
}
