<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTypeToProposalMilestone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proposal_milestone', function (Blueprint $table) {
            $table->string("type", 32)->default("proposal")->index("type_index");
            $table->foreignId("source_id")->nullable()->index("source_id_index");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposal_milestone', function (Blueprint $table) {
            $table->dropIndex("type_index");
            $table->dropIndex("source_id_index");

            $table->dropColumn("type");
            $table->dropColumn("source_id");
        });
    }
}
