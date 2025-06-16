<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logable', function (Blueprint $table) {
            $table->id();
            $table->foreignId("logable_id");
            $table->string("logable_type");
            $table->foreignId("user_id")->index("user_id_index");
            $table->string("action")->nullable();
            $table->text("before_change")->nullable();
            $table->text("after_change")->nullable();
            $table->text("changes")->nullable();
            $table->text("remark")->nullable();
            $table->timestamps();

            $table->index(['logable_id', 'logable_type'], 'logable_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logable');
    }
}
