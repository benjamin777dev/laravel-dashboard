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
            $table->string('buyerPackage')->nullable();
            $table->date('buyerClosingDate')->nullable();
            $table->string('buyerLenderEmail')->nullable();
            $table->string('buyerLenderPhone')->nullable();
            $table->string('buyerFeesCharged')->nullable();
            $table->string('buyerBuilderrepresent')->nullable();
            $table->string('builderCommisionPercent')->nullable();
            $table->string('builderCommision')->nullable();
            $table->string('contractExecuted')->nullable();
            $table->string('buyerAgency')->nullable();
            if (!Schema::hasColumn('submittals', 'formType')) {
                $table->string('formType')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submittals', function (Blueprint $table) {
            $table->dropColumn([
                'buyerPackage',
                'buyerClosingDate',
                'buyerLenderEmail',
                'buyerLenderPhone',
                'buyerFeesCharged',
                'buyerBuilderrepresent',
                'builderCommisionPercent',
                'builderCommision',
                'contractExecuted',
                'buyerAgency',
                'formType'
            ]);
        });
    }
};
