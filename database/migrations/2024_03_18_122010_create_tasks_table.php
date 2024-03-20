<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->dateTime('closed_time')->nullable();
            $table->unsignedBigInteger('who_id')->nullable(); // Assuming this is a foreign key referencing contacts table
            $table->string('created_by')->nullable();
            $table->string('currency')->nullable();
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('exchange_rate', 10, 2)->nullable();
            $table->string('import_batch')->nullable();
            $table->string('modified_by')->nullable();
            $table->string('priority')->nullable();
            $table->unsignedBigInteger('what_id')->nullable(); // Assuming this is a foreign key referencing related table
            $table->string('recurring_activity')->nullable();
            $table->string('status')->nullable();
            $table->string('subject')->nullable();
            $table->string('tag')->nullable();
            $table->unsignedBigInteger('owner')->nullable(); // Assuming this is a foreign key referencing users table
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('who_id')->references('id')->on('contacts')->onDelete('set null');
            $table->foreign('what_id')->references('id')->on('related_table')->onDelete('set null');
            $table->foreign('owner')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
