<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldUserIdToProposalProjectTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proposal_project_team', function (Blueprint $table) {
            $table->foreignId("user_id")->nullable()->index("user_id_index");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposal_project_team', function (Blueprint $table) {
            $table->dropColumn("user_id");
        });
    }
}
