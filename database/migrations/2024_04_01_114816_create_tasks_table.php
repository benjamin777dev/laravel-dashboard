<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatetasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamp('closed_time')->nullable();
            $table->string('who_id')->nullable();
            $table->string('created_by')->nullable();
            $table->string('currency')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->string('exchange_rate')->nullable();
            $table->string('import_batch')->nullable();
            $table->string('modified_by')->nullable();
            $table->string('priority')->nullable();
            $table->string('what_id')->nullable();
            $table->string('recurring_activity')->nullable();
            $table->string('status')->nullable();
            $table->string('subject')->nullable();
            $table->string('tag')->nullable();
            $table->string('owner')->nullable();
            $table->string('zoho_task_id')->nullable();
            $table->timestamp('created_time')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
