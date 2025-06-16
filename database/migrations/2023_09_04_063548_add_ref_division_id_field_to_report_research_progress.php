<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefDivisionIdFieldToReportResearchProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_research_progress', function (Blueprint $table) {
            $table->foreignId("ref_division_id")->nullable()->index("ref_division_id_index");
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
            $table->dropIndex("ref_division_id_index");
            $table->dropColumn("ref_division_id");
        });
    }
}
