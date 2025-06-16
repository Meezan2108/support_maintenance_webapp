<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalMilestoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_milestone', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->index('proposal_id_index');
            $table->string('activities');
            $table->dateTime('from');
            $table->dateTime('to')->nullable();
            $table->integer('order')->nullable();

            $table->integer('is_achieved')->nullable();
            $table->dateTime('completion_date')->nullable();

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
        Schema::dropIfExists('proposal_milestone');
    }
}
