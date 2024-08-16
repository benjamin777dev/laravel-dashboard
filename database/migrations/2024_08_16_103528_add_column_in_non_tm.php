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
        Schema::table('non_tms', function (Blueprint $table) {
           $table->text('resubmitting_why_list_all_changes')->nullable()->after('resubmit_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('non_tm', function (Blueprint $table) {
            //
        });
    }
};
