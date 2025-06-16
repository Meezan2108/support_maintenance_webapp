<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyticalServiceLabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analytical_service_lab', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index('user_id_index');
            $table->integer('no_sample');
            $table->integer('no_analysis');
            $table->integer('no_analysis_protocol');
            $table->dateTime('date');

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
        Schema::dropIfExists('analytical_service_lab');
    }
}
