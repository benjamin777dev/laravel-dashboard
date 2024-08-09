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
        Schema::table('templates', function (Blueprint $table) {
            // Modify 'name' column to be nullable
            $table->string('name')->nullable()->change();

            // Modify 'ownerId' column to be nullable
            $table->unsignedBigInteger('ownerId')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            // Revert 'name' column to not nullable
            $table->string('name')->nullable(false)->change();

            // Revert 'ownerId' column to not nullable
            $table->unsignedBigInteger('ownerId')->nullable(false)->change();
        });
    }
};
