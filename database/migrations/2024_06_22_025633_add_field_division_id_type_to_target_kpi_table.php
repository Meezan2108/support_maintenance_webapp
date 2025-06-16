<?php

use App\Models\TargetKpi;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldDivisionIdTypeToTargetKpiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('target_kpi', function (Blueprint $table) {
            $table->foreignId("division_id")->nullable()->index("division_id_index");
            $table->integer("type")->default(TargetKpi::TYPE_USER)->index("type_index");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('target_kpi', function (Blueprint $table) {
            $table->dropIndex("division_id_index");
            $table->dropIndex("type_index");

            $table->dropColumn("division_id");
            $table->dropColumn("type");
        });
    }
}
