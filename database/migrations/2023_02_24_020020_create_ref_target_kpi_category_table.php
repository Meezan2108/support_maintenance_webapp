<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefTargetKpiCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_target_kpi_category', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->nullable()->index('code_index');
            $table->string('description');
            $table->foreignId('parent_id')->nullable()->index('parent_id_index');
            $table->string('type', 32)->nullable()->index("type_index");
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
        Schema::dropIfExists('ref_target_kpi_category');
    }
}
