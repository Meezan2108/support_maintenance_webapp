<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMilestonePublicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milestone_publication', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_milestone_id')->index('report_milestone_id_index');
            $table->string('title')->nullable();
            $table->string('publisher')->nullable();
            $table->integer('ref_pub_type_id')->nullable()->index('ref_pub_type_id_index');
            $table->dateTime('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('milestone_publication');
    }
}
