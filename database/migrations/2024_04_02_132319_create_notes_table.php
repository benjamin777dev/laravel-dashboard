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
            $table->unsignedBigInteger('owner')->nullable();
            $table->unsignedBigInteger('related_to')->nullable();
            $table->string('related_to_type')->nullable();
            $table->string('zoho_note_id')->nullable();
            $table->string('note_content')->nullable();
            $table->string('created_time')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notes');
    }
}
