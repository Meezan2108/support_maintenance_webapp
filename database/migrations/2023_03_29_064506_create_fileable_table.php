<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fileable', function (Blueprint $table) {
            $table->id('id');
            $table->string("fileable_type");
            $table->foreignId("fileable_id");
            $table->string("access_key", 255)->index('access_key_index');
            $table->string("code_type", 32)->index("code_type_index");
            $table->string("file_name");
            $table->string("file_type");
            $table->bigInteger("file_size")->nullable();
            $table->binary("file");
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
        Schema::dropIfExists('fileable');
    }
}
