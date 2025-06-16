<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportQuarterlyFinancialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_quarterly_financial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_quarterly_id')->index('propsal_id_index');
            $table->bigInteger('total_recieved')->default(0);
            $table->bigInteger('total_expenditure')->default(0);
            $table->integer('is_inline_plan')->nullable();
            $table->text('reasons')->nullable();
            $table->text('proposed_action')->nullable();

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
        Schema::dropIfExists('report_quarterly_financial');
    }
}
