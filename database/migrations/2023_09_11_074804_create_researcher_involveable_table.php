<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearcherInvolveableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('researcher_involveable', function (Blueprint $table) {
            $table->id();

            $table->string("researcher_involveable_type");
            $table->foreignId("researcher_involveable_id");

            $table->foreignId("user_id")->index("user_id");
            $table->foreignId("kpi_achievement_id")->index("kpi_achievement_id_index");

            $table->string("name");
            $table->foreignId("ref_position_id")->nullable()->index("ref_position_id_index");
            $table->string("position")->nullable();

            $table->integer("type")->nullable()->comment("1:leader,2:researcher,3:staff");

            $table->timestamps();
            $table->softDeletes();

            $table->index(['researcher_involveable_type', 'researcher_involveable_id'], 'researcher_involveable_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('researcher_involveable');
    }
}
