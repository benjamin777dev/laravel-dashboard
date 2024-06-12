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
            $table->boolean('isNonTmCompleted')->default(true);
            $table->string('email')->nullable();
            $table->boolean('referral_fee_paid_out')->nullable();
            $table->boolean('home_warranty_paid_out_agent')->nullable();
            $table->boolean('any_additional_fees_charged')->nullable();
            $table->decimal('final_purchase_price', 10, 2)->nullable();
            $table->decimal('Commission', 10, 2)->nullable();
            $table->decimal('amount_to_chr_gives', 10, 2)->nullable();
            $table->text('agent_comments')->nullable();
            $table->text('other_commission_notes')->nullable();
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
