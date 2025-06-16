<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifableLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifable_log', function (Blueprint $table) {
            $table->id();

            $table->foreignId("notifable_id")->index("index_notifable_id");
            $table->foreignId("user_id")->index("index_user_id");
            $table->integer("status");

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
        Schema::dropIfExists('notifable_log');
    }
}
