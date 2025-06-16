<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvement', function (Blueprint $table) {
            $table->id();
            $table->string('approvable_type');
            $table->bigInteger('approvable_id');
            $table->foreignId('user_id')->index('user_id_index');
            $table->foreignId('role_id')->index('role_id_index');
            $table->integer('status');
            $table->text('comments')->nullable();
            $table->text('options')->nullable();
            $table->dateTime('date');
            $table->integer('version');

            $table->timestamps();
            $table->softDeletes();
            $table->index(['approvable_type', 'approvable_id'], 'approvable_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approvement');
    }
}
