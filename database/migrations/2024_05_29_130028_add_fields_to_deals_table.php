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
            $table->string('lead_agent')->nullable();
            $table->string('financing')->nullable();
            $table->string('modern_mortgage_lender')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn('lead_agent');
            $table->dropColumn('financing');
            $table->dropColumn('modern_mortgage_lender');
        });
    }
};
