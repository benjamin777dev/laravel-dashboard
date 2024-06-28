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
            $table->decimal('referralFeeAmount', 10, 2)->nullable();
            $table->string('referralFeeBrokerage')->nullable();
            $table->string('referralAgreement')->nullable();
            $table->string('hasW9Provided')->nullable();
            $table->decimal('homeWarrentyAmount', 10, 2)->nullable();
            $table->string('homeWarrentyDescription')->nullable();
            $table->decimal('additionalFeesAmount', 10, 2)->nullable();
            $table->string('additionalFeesDescription')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('non_tms', function (Blueprint $table) {
            $table->dropColumn(
                'referralFeeAmount',
                'referralFeeBrokerage',
                'referralAgreement',
                'hasW9Provided',
                'homeWarrentyAmount',
                'homeWarrentyDescription',
                'additionalFeesAmount',
                'additionalFeesDescription',
            );
        });
    }
};
