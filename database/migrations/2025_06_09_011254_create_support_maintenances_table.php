<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_maintenances', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->unique(); // SM25001
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained();
            $table->date('request_date');
            $table->string('reported_by');
            $table->string('department_unit')->nullable();
            $table->foreignId('issue_type_id')->constrained(); // from master table
            $table->text('description')->nullable();
            $table->foreignId('reported_to')->constrained('st_members'); // master
            $table->enum('priority', ['Low', 'Medium', 'High']);
            $table->enum('status', ['Pending', 'Done', 'Cancelled']);
            $table->date('starting_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->integer('duration_days')->nullable(); // auto-calculated
            $table->text('solution_summary')->nullable();
            $table->enum('follow_up_required', ['Yes', 'No']);
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('support_maintenances');
    }
}
