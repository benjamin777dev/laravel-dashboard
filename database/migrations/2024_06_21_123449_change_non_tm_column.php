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
            $table->string('dealId')->nullable()->change();
            $table->string('zoho_nontm_id')->nullable()->change();
            $table->string('referral_fee_paid_out')->nullable()->change();
            $table->string('home_warranty_paid_out_agent')->nullable()->change();
            $table->string('any_additional_fees_charged')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('non_tms', function (Blueprint $table) {
            $table->unsignedBigInteger('dealId')->nullable()->change();
            $table->unsignedBigInteger('zoho_nontm_id')->nullable()->change();
            $table->boolean('referral_fee_paid_out')->nullable()->change();
            $table->boolean('home_warranty_paid_out_agent')->nullable()->change();
            $table->boolean('any_additional_fees_charged')->nullable()->change();
        });
    }
};
