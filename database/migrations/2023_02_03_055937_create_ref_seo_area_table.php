<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefSeoAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_seo_area', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->unique('code_unique');
            $table->string('description');
            $table->string('ref_seo_group_id')->nullable()->index('ref_seo_group_id_index');
            $table->text('detail')->nullable();
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
            $table->datetime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_seo_area');
    }
}
