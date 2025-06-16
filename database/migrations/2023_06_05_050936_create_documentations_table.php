<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('description');
            $table->integer('category');
            $table->dateTime('submission_date');
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
        Schema::dropIfExists('documentations');
    }
}
