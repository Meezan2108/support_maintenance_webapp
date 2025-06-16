<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReminderSendLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminder_send_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId("reminder_id")->index("reminder_id_index");
            $table->foreignId("ref_id");
            $table->string("ref_type", 255);

            $table->string("recipient", 255);
            $table->integer("status");
            $table->timestamp("scheduled_at")->nullable();
            $table->timestamp("sent_at")->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['ref_id', 'ref_type'], 'ref_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reminder_send_log');
    }
}
