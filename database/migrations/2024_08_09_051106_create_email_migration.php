<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id(); 
            $table->json('toEmail'); 
            $table->json('ccEmail')->nullable(); 
            $table->json('bccEmail')->nullable(); 
            $table->string('fromEmail'); 
            $table->string('subject')->nullable(); 
            $table->longText('content')->nullable(); 
            $table->unsignedBigInteger('userId'); 
            $table->boolean('isEmailSent')->default(false); 
            $table->boolean('isDeleted')->default(false); 
            $table->string('sendEmailFrom')->nullable(); 
            $table->string('message_id')->nullable(); 
            $table->timestamps(); 

            // Foreign key constraint (optional)
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
