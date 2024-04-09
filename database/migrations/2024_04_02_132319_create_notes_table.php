<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatenotesTable extends Migration
{
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('zoho_note_id')->nullable();
            $table->unsignedBigInteger('owner')->nullable();
            $table->unsignedBigInteger('related_to')->nullable();
            $table->unsignedBigInteger('related_to_parent_record_id')->nullable();
            $table->unsignedBigInteger('related_to_module_id')->nullable();
            $table->string('related_to_type')->nullable();
            $table->text('note_content')->nullable();
            $table->timestamp('created_time')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notes');
    }
}
