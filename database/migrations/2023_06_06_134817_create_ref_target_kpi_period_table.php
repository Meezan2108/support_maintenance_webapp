<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefTargetKpiPeriodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_target_kpi_period', function (Blueprint $table) {
            $table->id();
            $table->string("code", 32)->nullable()->index("code_index");
            $table->string("description");
            $table->string("type", 32)->nullable()->index("type_index");
            $table->text("options")->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_target_kpi_period');
    }
}
