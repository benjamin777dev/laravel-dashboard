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
            $table->id(); // Auto-incrementing ID field
            $table->json('toEmail'); // Storing array of email IDs as JSON
            $table->json('ccEmail')->nullable(); // Optional array of CC email IDs as JSON
            $table->json('bccEmail')->nullable(); // Optional array of BCC email IDs as JSON
            $table->string('fromEmail'); // String for the sender's email
            $table->string('subject')->nullable(); // Email subject, optional
            $table->longText('content')->nullable(); // Email content, optional
            $table->unsignedBigInteger('userId'); // Reference to the user who sent the email
            $table->boolean('isEmailSent')->default(false); // Boolean to check if the email was sent
            $table->boolean('isDeleted')->default(false); // Boolean to check if the email was deleted
            $table->string('sendEmailFrom')->nullable(); // Service used to send the email, optional
            $table->string('message_id')->nullable(); // Message ID from the email service, optional
            $table->timestamps(); // Timestamps for created_at and updated_at

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
