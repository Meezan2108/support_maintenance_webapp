<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefProposalBenefitsItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_proposal_benefits_item', function (Blueprint $table) {
            $table->id();
            $table->integer('category')->index('category_index');
            $table->string('type', 100)->index('type_index')->nullable();
            $table->text('description');
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_proposal_benefits_item');
    }
}
