<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldRoleIdToEvaluationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluation', function (Blueprint $table) {
            $table->foreignId("role_id")
                ->after("evaluator_id")
                ->index("role_id_index")
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluation', function (Blueprint $table) {
            $table->dropIndex("role_id_index");
            $table->dropColumn("role_id");
        });
    }
}
