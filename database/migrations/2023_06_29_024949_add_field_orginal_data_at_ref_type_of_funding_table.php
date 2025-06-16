<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldOrginalDataAtRefTypeOfFundingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_type_of_funding', function (Blueprint $table) {
            $table->text('original_data')->nullable();
            $table->foreignId('original_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_type_of_funding', function (Blueprint $table) {
            $table->dropColumn('original_data');
            $table->dropColumn('original_id');
        });
    }
}
