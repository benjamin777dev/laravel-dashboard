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
         Schema::table('deals', function (Blueprint $table) {
            // Modify an existing column
            $table->unsignedBigInteger('contact_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            // Change the column back to its original state
            $table->string('contact_name')->nullable()->change(); // Assuming the original type was integer
        });
    }
};
