<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefReportEopBenefitsQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_report_eop_benefits_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ref_report_eop_benefits_section_id')->index('ref_report_eop_benefits_section_id');
            $table->text('content');
            $table->string('type');
            $table->text('options')->nullable();
            $table->integer('order');
            $table->text('rules')->nullable();

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
        Schema::dropIfExists('ref_report_eop_benefits_question');
    }
}
