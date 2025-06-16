<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportResearchProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_research_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index('user_id_index');
            $table->foreignId('ref_report_type_id')->index('ref_report_type_id_index');
            $table->foreignId('proposal_id')->index('proposal_id_index');

            $table->integer('year')->nullable();
            $table->text('focus_area')->nullable();
            $table->text('issue')->nullable();
            $table->text('strategy')->nullable();
            $table->text('program')->nullable();

            $table->dateTime('date');
            $table->text('background')->nullable();
            $table->text('result')->nullable();
            $table->text('summary')->nullable();

            $table->integer('approval_status');

            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('created_by')->nullable()->index('created_by_index');
            $table->foreignId('updated_by')->nullable()->index('updated_by_index');
            $table->foreignId('deleted_by')->nullable()->index('deleted_by_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_research_progress');
    }
}
