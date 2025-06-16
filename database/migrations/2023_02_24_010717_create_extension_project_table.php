<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtensionProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extension_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index('user_id_index');
            $table->foreignId('proposal_id')->index('proposal_id_index');
            $table->text('justification')->nullable();
            $table->text('new_fund')->nullable();
            $table->integer('duration')->nullable();
            $table->dateTime('date_end_extension')->nullable();
            $table->integer('balance_to_date')->nullable();
            $table->integer('approval_status')->nullable();

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
        Schema::dropIfExists('extension_project');
    }
}
