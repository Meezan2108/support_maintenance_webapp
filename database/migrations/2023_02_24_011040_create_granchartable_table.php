<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGranchartableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('granttchartable', function (Blueprint $table) {
            $table->id();
            $table->string('granttchartable_type');
            $table->foreignId('granttchartable_id');
            $table->integer('category')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->integer('order')->nullable();
            $table->text('options')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('granttchartable');
    }
}
