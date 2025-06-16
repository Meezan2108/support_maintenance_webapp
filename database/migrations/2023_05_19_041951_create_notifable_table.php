<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifable', function (Blueprint $table) {
            $table->id();;

            $table->string("notifable_type");
            $table->foreignId("notifable_id");
            $table->foreignId("template_id")->index("template_id_index");

            $table->string("target_model_type", 32)->index("target_model_type_index");
            $table->foreignId("target_model_id")->index("target_model_id_index")->nullable();
            $table->string("category", 32)->nullable();
            $table->text("data")->nullable();
            $table->text("options")->nullable();
            $table->bigInteger("status")->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['notifable_type', 'notifable_id'], 'notifable_index');
            $table->index(['target_model_type', 'target_model_id'], 'target_model_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifable');
    }
}
