<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('emails', function (Blueprint $table) {
            // Modify columns to allow NULL values
            $table->json('toEmail')->nullable()->change();
            $table->string('fromEmail')->nullable()->change();
            $table->unsignedBigInteger('userId')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('emails', function (Blueprint $table) {
            // Revert the changes made in the up method
            $table->json('toEmail')->nullable(false)->change();
            $table->string('fromEmail')->nullable(false)->change();
            $table->unsignedBigInteger('userId')->nullable(false)->change();
        });
    }
};
