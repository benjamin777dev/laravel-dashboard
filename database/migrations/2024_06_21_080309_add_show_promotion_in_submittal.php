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
        Schema::table('submittals', function (Blueprint $table) {
            if (!Schema::hasColumn('submittals', 'showPromotion')) {
                $table->boolean('showPromotion')->default(false);
            }
           
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submittals', function (Blueprint $table) {
           $table->dropColumn(
                'showPromotion'
            );
        });
    }
};
