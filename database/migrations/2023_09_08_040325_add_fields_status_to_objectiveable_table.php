<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsStatusToObjectiveableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectiveable', function (Blueprint $table) {
            $table->integer("status")->nullable()->index("status_index");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('objectiveable', function (Blueprint $table) {
            $table->dropIndex("status_index");
            $table->dropColumn("status");
        });
    }
}
