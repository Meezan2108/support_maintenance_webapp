<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMilestoneCommercializationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milestone_commercialization', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_milestone_id')->index('report_milestone_id_index');
            $table->integer('category')->index('category_index')->nullable();
            $table->string('name');
            $table->string('taker')->nullable();
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
        Schema::dropIfExists('milestone_commercialization');
    }
}
