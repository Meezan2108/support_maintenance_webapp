<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToReportMilestoneTable extends Migration
{

    // - planed milstone date 
    //     - reason (text)
    //     - corrective_action (text)
    //     - revised_completion_date (date)
    // - Impact on Project Schedule
    //     - request extension : (int) month
    //     - new date completion_date : (date)
    //     - reason for extension : (text)

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_milestone', function (Blueprint $table) {
            $table->text("reason_for_extension")->after("comments")->nullable();
            $table->dateTime("new_completion_date")->after("comments")->nullable();
            $table->integer("request_extension")->after("comments")->nullable()->comment("in month");

            $table->dateTime("revised_completion_date")->after("comments")->nullable();
            $table->text("corrective_action")->after("comments")->nullable();
            $table->text("reason_not_achieved")->after("comments")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_milestone', function (Blueprint $table) {
            $table->dropColumn("reason_not_achieved");
            $table->dropColumn("corrective_action");
            $table->dropColumn("revised_completion_date");

            $table->dropColumn("request_extension");
            $table->dropColumn("new_completion_date");
            $table->dropColumn("reason_for_extension");
        });
    }
}
