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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('deal_name');
            $table->unsignedBigInteger('owner_id'); // Assuming this refers to a user
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('stage');
            $table->date('closing_date')->nullable();
            $table->text('description')->nullable();
            $table->string('lead_source')->nullable();
            $table->unsignedBigInteger('contact_name_id')->nullable(); // Assuming this refers to a contact
            $table->string('account_name')->nullable();
            $table->decimal('probability', 5, 2)->nullable();
            $table->string('next_step')->nullable();
            $table->decimal('expected_revenue', 15, 2)->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        
            // Foreign keys (if applicable)
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
