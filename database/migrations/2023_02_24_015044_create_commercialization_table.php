<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommercializationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commercialization', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index('user_id_index');
            $table->integer('category')->comment('1:Products; 2:Technologies');
            $table->string('name');
            $table->text('taker');
            $table->dateTime('date');

            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('created_by')->nullable()->index('created_by_index');
            $table->foreignId('updated_by')->nullable()->index('updated_by_index');
            $table->foreignId('deleted_by')->nullable()->index('deleted_by_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commercialization');
    }
}
