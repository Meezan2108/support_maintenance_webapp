<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportEndProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_end_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->index('proposal_id_index');
            $table->foreignId('user_id')->index('user_id_index');

            $table->integer('project_duration')->nullable();
            $table->bigInteger('budget_approved')->nullable();

            $table->string('priority_area')->nullable();

            $table->text('original_objectives')->nullable();
            $table->text('objectives_achieved')->nullable();
            $table->text('objectives_not_achieved')->nullable();

            $table->text('tech_approach')->nullable();
            $table->text('asses_research')->nullable();
            $table->text('asses_schedule')->nullable();
            $table->text('asses_cost')->nullable();
            $table->text('additional_fund')->nullable();

            $table->text('direct_outputs')->nullable();
            $table->text('outcomes')->nullable();
            $table->text('national_impact')->nullable();

            $table->integer('approval_status')->nullable();

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
        Schema::dropIfExists('report_end_project');
    }
}
