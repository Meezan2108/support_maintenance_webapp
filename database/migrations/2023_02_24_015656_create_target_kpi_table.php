<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetKpiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_kpi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index('user_id_index');
            $table->integer('year');
            $table->foreignId('period_id')->nullable()->index('period_id_index');
            $table->foreignId('category_id')->nullable()->index('category_id_index');
            $table->foreignId('sub_category_id')->nullable()->index('sub_category_id_index');
            $table->integer('target')->nullable();
            $table->dateTime('date')->nullable();
            $table->integer('approval_status')->nullable()->index('approval_status_index');

            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('created_by')->nullable()->index('created_by_index');
            $table->foreignId('updated_by')->nullable()->index('updated_by_index');
            $table->foreignId('deleted_by')->nullable()->index('deleted_by_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('target_kpi');
    }
}
