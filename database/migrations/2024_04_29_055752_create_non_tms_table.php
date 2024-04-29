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
        Schema::create('non_tms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->date('closed_date')->nullable();
            $table->unsignedBigInteger('userId')->nullable();
            $table->unsignedBigInteger('dealId')->nullable();
            $table->unsignedBigInteger('zoho_nontm_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_tms');
    }
};
