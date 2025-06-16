<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefReportEopBenefitsSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_report_eop_benefits_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ref_report_eop_benefits_group_id')->index('ref_report_eop_benefits_group_id_index');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order');

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
        Schema::dropIfExists('ref_report_eop_benefits_section');
    }
}
