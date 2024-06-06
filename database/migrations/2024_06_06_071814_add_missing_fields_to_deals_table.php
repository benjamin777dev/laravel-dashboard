<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToDealsTable extends Migration
{
    public function up()
    {
        Schema::table('deals', function (Blueprint $table) {
            // Adding missing fields
            $table->text('brokerment_id')->nullable();
            $table->text('owner_name')->nullable();
            $table->text('owner_id')->nullable();
            $table->text('owner_email')->nullable();
            $table->text('final_commission_for_co_op_agent_flat_fee')->nullable();
            $table->boolean('compliance_check_complete')->default(false);
            $table->text('html_report')->nullable();
            $table->text('full_address')->nullable();
            $table->text('currency')->nullable();
            $table->text('tm_name_id')->nullable();
            $table->text('created_by_name')->nullable();
            $table->text('created_by_id')->nullable();
            $table->text('created_by_email')->nullable();
            $table->text('original_co_op_commission')->nullable();
            $table->text('original_co_op_commission_flat_fee')->nullable();
            $table->text('lead_agent_id')->nullable();
            $table->text('lead_agent_name')->nullable();
            $table->text('lead_agent_email')->nullable();
            $table->text('contact_name_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('deals', function (Blueprint $table) {
            // Dropping the added fields in the down method
            $table->dropColumn([
                'brokerment_id', 'owner_name', 'owner_id', 'owner_email',
                'final_commission_for_co_op_agent_flat_fee',
                'compliance_check_complete', 'html_report', 'full_address',
                'currency', 'tm_name_id', 'created_by_name',
                'created_by_id', 'created_by_email', 'original_co_op_commission',
                'original_co_op_commission_flat_fee', 'lead_agent_id', 'lead_agent_name',
                'lead_agent_email', 'contact_name_id', 
            ]);
        });
    }
}
