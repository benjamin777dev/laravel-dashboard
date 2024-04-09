<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatedealsTable extends Migration
{
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('zip')->nullable();
            $table->text('personal_transaction')->nullable();
            $table->boolean('double_ended')->default(false);
            $table->unsignedBigInteger('userID')->nullable();
            $table->string('address')->nullable();
            $table->string('representing')->nullable();
            $table->string('client_name_only')->nullable();
            $table->decimal('commission', 8, 2)->nullable(); // Adjusted decimal precision
            $table->string('probable_volume')->nullable();
            $table->string('lender_company')->nullable();
            $table->timestamp('closing_date')->nullable();
            $table->string('ownership_type')->nullable();
            $table->boolean('needs_new_date2')->default(false);
            $table->string('deal_name')->nullable();
            $table->string('tm_preference')->nullable();
            $table->string('stage')->nullable();
            $table->decimal('sale_price', 15, 2)->nullable(); // Adjusted decimal precision
            $table->string('zoho_deal_id')->nullable();
            $table->string('pipeline1')->nullable();
            $table->decimal('pipeline_probability', 5, 2)->nullable();
            $table->timestamp('zoho_deal_createdTime')->nullable();
            $table->string('property_type')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('lender_company_name')->nullable();
            $table->string('client_name_primary')->nullable();
            $table->string('lender_name')->nullable();
            $table->string('potential_gci')->nullable();
            $table->string('created_by')->nullable();
            $table->unsignedBigInteger('contractId')->nullable(); // Modified to unsignedBigInteger
            $table->unsignedBigInteger('contactId')->nullable(); // Modified to unsignedBigInteger

            $table->foreign('userID')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('contractId')->references('id')->on('contracts')->onDelete('set null');
            // $table->foreign('contactId')->references('id')->on('contacts')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deals');
    }
}
