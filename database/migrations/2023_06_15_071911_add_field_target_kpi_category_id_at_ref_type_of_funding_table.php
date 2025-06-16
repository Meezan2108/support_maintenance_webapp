<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTargetKpiCategoryIdAtRefTypeOfFundingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_type_of_funding', function (Blueprint $table) {
            $table->foreignId("ref_target_kpi_category_id")->nullable()
                ->index("ref_target_kpi_category_id_index")
                ->after("code");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_type_of_funding', function (Blueprint $table) {
            $table->dropIndex("ref_target_kpi_category_id_index");
            $table->dropColumn("ref_target_kpi_category_id");
        });
    }
}
