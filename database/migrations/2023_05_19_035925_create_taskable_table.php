<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taskable', function (Blueprint $table) {
            $table->id();

            $table->string("taskable_type");
            $table->foreignId("taskable_id");

            $table->string("code_type", 32)->index("code_type_index")->comment("group, user");
            $table->foreignId("model_id")->index("model_id_index");
            $table->foreignId("submited_user_id")->index("submited_user_id");

            $table->string("code_id")->nullable();
            $table->string("title")->nullable();
            $table->string("category")->nullable();
            $table->text("link")->nullable();
            $table->text("options")->nullable();
            $table->integer("approval_status")->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['taskable_type', 'taskable_id'], 'taskable_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taskable');
    }
}
