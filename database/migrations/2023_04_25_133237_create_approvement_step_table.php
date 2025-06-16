<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovementStepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvement_step', function (Blueprint $table) {
            $table->id();
            $table->string('approvementstepable_type');
            $table->bigInteger('approvementstepable_id');
            $table->integer("step");
            $table->foreignId("reviewer_1")->nullable()->index("reviewer_1_index");
            $table->foreignId("reviewer_2")->nullable()->index("reviewer_2_index");
            $table->foreignId("reviewer_3")->nullable()->index("reviewer_3_index");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approvement_step');
    }
}
