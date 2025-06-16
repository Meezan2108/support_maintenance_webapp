<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOriginalableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('originalable', function (Blueprint $table) {
            $table->id();
            $table->string("originalable_type");
            $table->foreignId("originalable_id");
            $table->string("table_source", 255)->index("table_source_index");
            $table->foreignId("original_id")->index("original_id_index");
            $table->foreignId("original_id_alt")->nullable();
            $table->text("original_data");

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
        Schema::dropIfExists('originalable');
    }
}
