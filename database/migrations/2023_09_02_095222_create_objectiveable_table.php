<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectiveableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objectiveable', function (Blueprint $table) {
            $table->id();

            $table->foreignId("objectiveable_id");
            $table->string("objectiveable_type");

            $table->text("description");
            $table->integer("order")->nullable();

            $table->timestamps();

            $table->index(['objectiveable_id', 'objectiveable_type'], 'objectiveable_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('objectiveable');
    }
}
