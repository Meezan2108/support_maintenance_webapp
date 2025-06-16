<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
                  $table->string('project_name');
                  $table->foreignId('client_id')->constrained()->onDelete('cascade');
                  $table->text('description')->nullable();
                  $table->date('start_date');
                  $table->date('end_date');
                  $table->string('status')->default('Active');
                  $table->foreignId('developer_id')->nullable()->constrained('developers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('projects', function (Blueprint $table) {
        $table->dropColumn('developer');
        });
        
        Schema::dropIfExists('projects');
    }
}
