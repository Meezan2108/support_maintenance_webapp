<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReminderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminder', function (Blueprint $table) {
            $table->id();
            $table->foreignId("ref_reminder_category_id")->index("ref_reminder_category_id_index");
            $table->integer("is_manual");
            $table->timestamp("scheduled_at")->nullable();
            $table->integer("repeat_type")->nullable();
            $table->integer("status");
            $table->text("notes")->nullable();
            $table->text("options")->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreignId("created_by")->nullable();
            $table->foreignId("updated_by")->nullable();
            $table->foreignId("deleted_by")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reminder');
    }
}
