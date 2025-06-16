<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefEvaluationQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_evaluation_question', function (Blueprint $table) {
            $table->id();
            $table->integer('category')->index('category_index');
            $table->string('type')->nullable();
            $table->text('description');
            $table->text('options');
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
        Schema::dropIfExists('ref_evaluation_question');
    }
}
