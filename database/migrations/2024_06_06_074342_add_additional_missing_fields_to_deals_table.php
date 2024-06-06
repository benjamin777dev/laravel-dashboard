<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalMissingFieldsToDealsTable extends Migration
{
    public function up()
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->text('approval_state')->nullable();
            $table->text('cda_notes')->nullable();
            $table->boolean('check_received')->default(false);
            $table->text('chr_name')->nullable();
            $table->text('commission_flat_fee')->nullable();
            $table->text('contract_time_of_day_deadline')->nullable();
            $table->timestamp('create_date')->nullable();
            $table->boolean('deadline_emails')->default(false);
            $table->decimal('exchange_rate', 8, 2)->nullable();
            $table->text('final_commission_for_agent')->nullable();
            $table->text('import_batch_id')->nullable();
            $table->timestamp('lead_conversion_time')->nullable();
            $table->boolean('locked_s')->default(false);
            $table->text('marketing_specialist')->nullable();
            $table->text('modified_by_email')->nullable();
            $table->text('modified_by_id')->nullable();
            $table->text('modified_by_name')->nullable();
            $table->timestamp('modified_time')->nullable();
            $table->text('most_recent_note')->nullable();
            $table->text('original_commission_for_agent_flat_fee')->nullable();
            $table->decimal('original_listing_price', 15, 2)->nullable();
            $table->integer('overall_sales_duration')->nullable();
            $table->text('primary_contact_email')->nullable();
            $table->decimal('probability', 5, 2)->nullable();
            $table->text('review_process')->nullable();
            $table->integer('sales_cycle_duration')->nullable();
            $table->boolean('status_reports')->default(false);
            $table->text('tag')->nullable();
            $table->boolean('tm_audit_complete')->default(false);
            $table->text('transaction_code')->nullable();
            $table->boolean('under_contract')->default(false);
            $table->text('z_project_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn([
                'approval_state', 'cda_notes', 'check_received', 'chr_name', 
                'commission_flat_fee', 'contract_time_of_day_deadline', 'create_date', 
                'deadline_emails', 'exchange_rate', 'final_commission_for_agent', 
                'import_batch_id', 'lead_conversion_time', 'locked_s', 'marketing_specialist', 
                'modified_by_email', 'modified_by_id', 'modified_by_name', 
                'modified_time', 'most_recent_note', 'original_commission_for_agent_flat_fee', 
                'original_listing_price', 'overall_sales_duration', 'primary_contact_email', 
                'probability', 'review_process', 'sales_cycle_duration', 
                'status_reports', 'tag', 'tm_audit_complete', 'transaction_code', 
                'under_contract', 'z_project_id'
            ]);
        });
    }
}
