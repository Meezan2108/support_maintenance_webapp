<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldUserIdNullableToTargetKpiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('target_kpi', function (Blueprint $table) {
            $table->foreignId("user_id")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('target_kpi', function (Blueprint $table) {
            $table->foreignId("user_id")->nullable(false)->change();
        });
    }
}
