<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('proposal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index("user_id_index");
            $table->integer('proposal_type')->index('proposal_type_index');
            $table->foreignId('ref_type_of_funding_id')->index('ref_type_of_funding_id_index');
            $table->integer('project_leader_type');
            $table->text('project_title');
            $table->string('application_id')->index('application_id_index')->nullable();
            $table->text('ptj_code')->nullable();
            $table->string('project_number')->nullable();
            $table->string('project_leader_name')->nullable();
            $table->string('project_leader_nric')->nullable();
            $table->string('institution')->nullable();
            $table->string('grade')->nullable();
            $table->string('working_address')->nullable();
            $table->string('keywords')->nullable();
            $table->string('specific_objective')->nullable();

            $table->foreignId('ref_research_type_id')->index('ref_research_type_id_index')->nullable();
            $table->foreignId('ref_research_cluster_id')->index('ref_research_cluster_id_index')->nullable();
            $table->foreignId('ref_seo_category_id')->index('ref_seo_category_id_index')->nullable();
            $table->foreignId('ref_seo_group_id')->index('ref_seo_group_id_index')->nullable();
            $table->foreignId('ref_seo_area_id')->index('ref_seo_area_id_index')->nullable();

            $table->text('research_location')->nullable();
            $table->text('project_summary')->nullable();
            $table->text('problem_statement')->nullable();
            $table->text('hypothesis')->nullable();
            $table->text('research_question')->nullable();
            $table->text('literature_review')->nullable();
            $table->text('relevance_goverment_policy')->nullable();
            $table->text('reference')->nullable();
            $table->text('related_research')->nullable();

            $table->text('research_methodology')->nullable();
            $table->text("risk_factor")->nullable();
            $table->text("risk_technical")->nullable();
            $table->text("risk_timing")->nullable();
            $table->text("risk_budget")->nullable();

            $table->dateTime('schedule_start_date')->nullable();
            $table->dateTime('schedule_completion_date')->nullable();
            $table->integer('schedule_duration')->nullable();

            $table->text('economic_contributions')->default('');

            $table->integer('approval_status')->index('approval_status_index')->default(0);
            $table->integer('project_status')->index('project_status_index')->default(0);

            $table->bigInteger('total_cost')->nullable();
            $table->bigInteger('approved_cost')->nullable();


            $table->foreignId('reviewer_1')->nullable()->index('reviewer_1_index');
            $table->foreignId('reviewer_2')->nullable()->index('reviewer_2_index');
            $table->foreignId('reviewer_3')->nullable()->index('reviewer_3_index');

            $table->foreignId('rmc_id')->nullable()->index('rmc_id_index');
            $table->integer('is_by_rmc')->default(0)->index('is_by_rmc_index');
            $table->dateTime('submit_at')->nullable();
            $table->dateTime('approval_at')->nullable();

            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('proposal');
    }
}
