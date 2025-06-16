<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportEopBenefitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_eop_benefit', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('report_end_project_id')->index('report_end_project_id_index');
            $table->bigInteger('ref_report_eop_benefits_question_id')->index('ref_report_eop_benefits_question_id_index');
            $table->text('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_eop_benefit');
    }
}
