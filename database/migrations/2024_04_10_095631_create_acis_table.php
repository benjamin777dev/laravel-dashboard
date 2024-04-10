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
        Schema::create('acis', function (Blueprint $table) {
            $table->id();
            $table->date('closing_date')->nullable();
            $table->integer('current_year')->nullable();
            $table->integer('agent_check_amount')->nullable();
            $table->unsignedBigInteger('userId')->nullable();
            $table->string('irs_reported_1099_income_for_this_transaction')->nullable();
            $table->string('stage')->nullable();
            $table->integer('total')->nullable();
            $table->unsignedBigInteger('zoho_aci_id')->nullable();
            $table->unsignedBigInteger('dealId')->nullable();
            $table->string('agentName')->nullable();
            $table->integer('less_split_to_chr')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acis');
    }
};
