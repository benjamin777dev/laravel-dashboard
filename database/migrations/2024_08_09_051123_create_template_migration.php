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
        Schema::create('templates', function (Blueprint $table) {
            $table->id(); 
            $table->string('name'); 
            $table->unsignedBigInteger('ownerId'); 
            $table->string('subject')->nullable(); 
            $table->boolean('active')->default(true); 
            $table->boolean('favorite')->default(false); 
            $table->boolean('consent_linked')->default(false); 
            $table->string('associated')->nullable(); 
            $table->string('folder')->nullable();
            $table->string('templateType')->nullable(); 
            $table->longText('content')->nullable(); 
            $table->string('zoho_template_id')->nullable(); 
            $table->timestamps(); 

            // Foreign key constraint (optional)
            $table->foreign('ownerId')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
