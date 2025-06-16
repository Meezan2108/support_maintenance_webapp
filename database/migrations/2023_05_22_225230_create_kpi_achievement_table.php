<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiAchievementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_achievement', function (Blueprint $table) {
            $table->id();

            $table->foreignId("user_id")->index("index_user_id");
            $table->text("title");
            $table->foreignId("category_id");
            $table->string("reff_type");
            $table->bigInteger("reff_id");
            $table->integer("approval_status")->nullable()->index("index_approval_status");
            $table->dateTime('date');

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
        Schema::dropIfExists('kpi_achievement');
    }
}
