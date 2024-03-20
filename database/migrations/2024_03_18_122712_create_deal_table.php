<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('address')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->unsignedBigInteger('brokermint_id')->nullable();
            $table->unsignedBigInteger('campaign_source')->nullable(); // Assuming this is a foreign key referencing another table
            $table->string('cda_notes')->nullable();
            $table->boolean('check_received')->default(false);
            $table->boolean('checkbox_14')->default(false);
            $table->string('chr_name')->nullable();
            $table->string('city')->nullable();
            $table->unsignedBigInteger('primary_contact')->nullable(); // Assuming this is a foreign key referencing contacts table
            $table->string('client_name_primary')->nullable();
            $table->string('client_name_only')->nullable();
            $table->date('closing_date')->nullable();
            $table->decimal('commission', 5, 2)->nullable();
            $table->boolean('compliance_check_complete')->default(false);
            $table->unsignedBigInteger('contact_name')->nullable(); // Assuming this is a foreign key referencing contacts table
            $table->unsignedBigInteger('contract')->nullable(); // Assuming this is a foreign key referencing contracts table
            $table->date('create_date')->nullable();
            $table->string('created_by')->nullable();
            $table->string('currency')->nullable();
            $table->boolean('deadline_emails')->default(false);
            $table->text('description')->nullable();
            $table->string('double_ended')->default(false);
            $table->decimal('exchange_rate', 10, 4)->nullable();
            $table->decimal('expected_revenue', 10, 2)->nullable();
            $table->string('financing')->nullable();
            $table->boolean('first_contract')->default(false);
            $table->string('full_address')->nullable();
            $table->text('html_report')->nullable();
            $table->string('import_batch_id')->nullable();
            $table->unsignedBigInteger('lead_source')->nullable(); // Assuming this is a foreign key referencing another table
            $table->string('lead_source_from')->nullable();
            $table->unsignedBigInteger('lender_company')->nullable(); // Assuming this is a foreign key referencing another table
            $table->string('lender_company_name')->nullable();
            $table->string('lender_name')->nullable();
            $table->decimal('loan_amount', 10, 2)->nullable();
            $table->string('loan_type')->nullable();
            $table->string('mls_no')->nullable();
            $table->unsignedBigInteger('modern_mortgage_lender')->nullable(); // Assuming this is a foreign key referencing another table
            $table->string('modified_by')->nullable();
            $table->text('most_recent_note')->nullable();
            $table->string('needs_new_date')->nullable();
            $table->date('needs_new_date1')->nullable();
            $table->boolean('needs_new_date2')->default(false);
            $table->string('next_step')->nullable();
            $table->string('ownership_type')->nullable();
            $table->decimal('pipeline_probability', 5, 2)->nullable();
            $table->string('potential_gci')->nullable();
            $table->unsignedBigInteger('primary_contact_email')->nullable(); // Assuming this is a foreign key referencing contacts table
            $table->unsignedBigInteger('primary_contact1')->nullable(); // Assuming this is a foreign key referencing contacts table
            $table->decimal('probability', 5, 2)->nullable();
            $table->string('pipeline1')->nullable();
            $table->string('probable_volume')->nullable();
            $table->string('property_type')->nullable();
            $table->string('reason_for_loss')->nullable();
            $table->string('representing')->nullable();
            $table->boolean('review_gen_opt_out')->default(false);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('seller_concession_amount', 10, 2)->nullable();
            $table->string('stage')->nullable();
            $table->string('state')->nullable();
            $table->boolean('status_reports')->default(false);
            $table->boolean('t')->default(false);
            $table->string('tag')->nullable();
            $table->string('contract_time_of_day_deadline')->nullable();
            $table->boolean('tm_audit_complete')->default(false);
            $table->unsignedBigInteger('tm_name')->nullable(); // Assuming this is a foreign key referencing users table
            $table->string('tm_preference')->nullable();
            $table->string('transaction_code')->nullable();
            $table->unsignedBigInteger('record_image')->nullable(); // Assuming this is a foreign key referencing another table
            $table->string('deal_name')->nullable();
            $table->unsignedBigInteger('owner')->nullable(); // Assuming this is a foreign key referencing users table
            $table->string('transaction_type')->nullable();
            $table->string('type')->nullable();
            $table->boolean('under_contract')->default(false);
            $table->boolean('using_tm')->default(false);
            $table->string('z_project_id')->nullable();
            $table->string('zip')->nullable();
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('campaign_source')->references('id')->on('campaigns')->onDelete('set null');
            $table->foreign('primary_contact')->references('id')->on('contacts')->onDelete('set null');
            $table->foreign('contact_name')->references('id')->on('contacts')->onDelete('set null');
            $table->foreign('contract')->references('id')->on('contracts')->onDelete('set null');
            $table->foreign('lead_source')->references('id')->on('lead_sources')->onDelete('set null');
            $table->foreign('lender_company')->references('id')->on('lender_companies')->onDelete('set null');
            $table->foreign('modern_mortgage_lender')->references('id')->on('modern_mortgage_lenders')->onDelete('set null');
            $table->foreign('primary_contact_email')->references('id')->on('contacts')->onDelete('set null');
            $table->foreign('primary_contact1')->references('id')->on('contacts')->onDelete('set null');
            $table->foreign('tm_name')->references('id')->on('users')->onDelete('set null');
            $table->foreign('record_image')->references('id')->on('images')->onDelete('set null');
            $table->foreign('owner')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deals');
    }
}
