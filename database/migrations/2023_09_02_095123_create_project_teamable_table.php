<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTeamableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_teamable', function (Blueprint $table) {
            $table->id();

            $table->foreignId("project_teamable_id");
            $table->string("project_teamable_type");

            $table->foreignId("user_id")->nullable();
            $table->string('name');

            $table->foreignId("ref_position_id")->nullable();
            $table->string('position')->nullable();

            $table->integer('type')->index('type_index');

            $table->string('organization')->nullable();
            $table->string('man_month')->nullable();
            $table->timestamps();

            $table->index(['project_teamable_id', 'project_teamable_type'], 'project_teamable_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_teamable');
    }
}
