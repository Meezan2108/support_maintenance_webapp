<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportQfDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_qf_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_quarterly_financial_id')->index('report_quarterly_financial_id_index');
            $table->foreignId('ref_project_cost_series_id')->index('ref_project_cost_series_id_index');
            $table->bigInteger('total_approved')->default(0);
            $table->bigInteger('total_recieved')->default(0);
            $table->bigInteger('total_expenditure')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_qf_detail');
    }
}
