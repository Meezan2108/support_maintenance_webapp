<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableAtProposalResearcherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proposal_researcher', function (Blueprint $table) {
            $table->string('tel_no', 64)->nullable()->change();
            $table->string('fax_no', 64)->nullable()->change();
            $table->string('email', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposal_researcher', function (Blueprint $table) {
            $table->string('tel_no', 64)->change();
            $table->string('fax_no', 64)->change();
            $table->string('email', 100)->change();
        });
    }
}
