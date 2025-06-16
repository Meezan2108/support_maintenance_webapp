<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportQuarterlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_quarterly', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->index("index_user_id");
            $table->foreignId("proposal_id")->index("index_proposal_id");
            $table->integer("report_type");
            $table->string("report_type_code", 16);
            $table->integer("year")->nullable();
            $table->integer("quarter")->nullable();
            $table->integer("approval_status")->nullable();

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
        Schema::dropIfExists('report_quarterly');
    }
}
