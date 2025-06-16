<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluation_answer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->index('evaluation_id_index');
            $table->foreignId('ref_answer_category_id')->index('ref_answer_category_id_index');
            $table->string('answer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluation_answer');
    }
}
