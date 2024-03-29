<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactTable extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->boolean('outsourced_mktg_3d_zillow_tour')->default(false);
            $table->string('abc_status')->nullable();
            $table->string('abcd')->nullable();
            $table->unsignedInteger('actual_agent_count')->nullable();
            $table->boolean('add_contract_dates_to_calendar')->default(false);
            $table->unsignedBigInteger('agent_assistant')->nullable();
            $table->string('agent_name_on_marketing')->nullable();
            $table->decimal('chr_gives_amount', 10, 2)->nullable();
            $table->string('assistant')->nullable();
            $table->string('asst_phone')->nullable();
            $table->string('auto_address')->nullable();
            $table->decimal('average_time_spent_minutes', 10, 2)->nullable();
            $table->unsignedBigInteger('brokermint_id')->unique()->nullable();
            $table->string('business_info')->nullable();
            $table->string('business_name')->nullable();
            $table->string('checks_payable_to')->nullable();
            $table->string('chr_relationship')->nullable();
            $table->string('closer_name_phone')->nullable();
            $table->boolean('coming_soon')->default(false);
            $table->unsignedBigInteger('record_image')->nullable();
            $table->unsignedBigInteger('owner')->nullable();
            $table->string('contract_dates_time_on_calendar')->nullable();
            $table->string('copier_code')->nullable();
            $table->string('created_by')->nullable();
            $table->string('culture_index')->nullable();
            $table->string('currency')->nullable();
            $table->boolean('current_annual_academy')->default(false);
            $table->date('daily_email')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->unsignedInteger('days_visited')->nullable();
            $table->unsignedInteger('default_commission_plan_id')->nullable();
            $table->string('delivery_name_address')->nullable();
            $table->string('department')->nullable();
            $table->string('describe_buyer_fees')->nullable();
            $table->string('describe_seller_fees')->nullable();
            $table->text('description')->nullable(); // Converted to TEXT
            $table->boolean('draft_showing_instructions')->default(false);
            $table->decimal('effective_agent_count', 10, 2)->nullable();
            $table->string('email')->nullable();
            $table->boolean('email_blast_opt_in')->default(false);
            $table->boolean('email_blast_to_reverse_prospect_list')->default(false);
            $table->boolean('email_blast_to_sphere')->default(false);
            $table->boolean('email_blast')->default(false);
            $table->string('email_cc_1')->nullable();
            $table->string('email_cc_2')->nullable();
            $table->boolean('email_opt_in')->default(false);
            $table->boolean('email_opt_out')->default(false);
            $table->text('email_signature')->nullable(); // Converted to TEXT
            $table->boolean('email_validated')->default(false);
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('salutation_s')->nullable();
            $table->boolean('events1')->default(false);
            $table->decimal('exchange_rate', 10, 4)->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('fax')->nullable();
            $table->string('feature_cards_or_sheets')->nullable();
            $table->decimal('fees_charged_to_buyer_at_closing', 10, 2)->nullable();
            $table->decimal('fees_charged_to_seller_at_closing', 10, 2)->nullable();
            $table->string('first_name')->nullable();
            $table->string('first_visited_url')->nullable();
            $table->dateTime('first_visited_time')->nullable();
            $table->boolean('outsourced_mktg_floorplans')->default(false);
            $table->string('role')->nullable();
            $table->string('google_business')->nullable();
            $table->string('google_business_page_url')->nullable();
            $table->unsignedBigInteger('groups')->nullable();
            $table->text('groups_tags')->nullable(); // Converted to TEXT
            $table->string('has_address')->nullable();
            $table->string('has_email')->nullable();
            $table->string('has_missing_important_date')->nullable();
            $table->string('home_phone')->nullable();
            $table->boolean('homebot')->default(false);
            $table->string('import_batch')->nullable();
            $table->string('import_id')->nullable();
            $table->boolean('important_date_added')->default(false);
            $table->boolean('include_insights_in_intro')->default(false);
            $table->decimal('income_goal', 10, 2)->nullable();
            $table->decimal('initial_cap', 10, 2)->nullable();
            $table->decimal('initial_split', 5, 2)->nullable();
            $table->string('instagram_url')->nullable();
            $table->boolean('invalid_address_usps')->default(false);
            $table->boolean('is_active')->default(false);
            $table->dateTime('last_called')->nullable();
            $table->dateTime('last_emailed')->nullable();
            $table->string('last_name')->nullable();
            $table->string('layout')->nullable();
            $table->string('lead_source')->nullable();
            $table->string('lead_source_detail')->nullable();
            $table->string('lender_company_name')->nullable();
            $table->string('lender_email')->nullable();
            $table->string('lender_name')->nullable();
            $table->date('license_expiration_date')->nullable();
            $table->string('license_number')->nullable();
            $table->date('license_start_date')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('mailing_city')->nullable();
            $table->string('mailing_country')->nullable();
            $table->string('mailing_state')->nullable();
            $table->string('mailing_street')->nullable();
            $table->string('mailing_zip')->nullable();
            $table->boolean('mailings')->default(false);
            $table->string('market_area')->nullable();
            $table->boolean('market_mailer_opt_in')->default(false);
            $table->boolean('outsourced_mktg_matterport')->default(false);
            $table->string('missing')->nullable();
            $table->boolean('missing_abcd')->default(false);
            $table->string('mls_id')->nullable();
            $table->boolean('mls_ires')->default(false);
            $table->boolean('mls_navica')->default(false);
            $table->boolean('mls_ppar')->default(false);
            $table->boolean('mls_recolorado')->default(false);
            $table->string('mobile')->nullable();
            $table->string('modified_by')->nullable();
            $table->dateTime('last_visited_time')->nullable();
            $table->boolean('need_o_e')->default(false);
            $table->unsignedBigInteger('non_tm_assignment')->nullable();
            $table->boolean('notepad_mailer_opt_in')->default(false);
            $table->boolean('notepads')->default(false);
            $table->unsignedInteger('number_of_chats')->nullable();
            $table->boolean('outsourced_mktg_onsite_video')->default(false);
            $table->string('other_city')->nullable();
            $table->string('other_country')->nullable();
            $table->string('other_phone')->nullable();
            $table->string('other_state')->nullable();
            $table->string('other_street')->nullable();
            $table->string('other_zip')->nullable();
            $table->unsignedBigInteger('peer_advisor')->nullable();
            $table->string('perfect')->nullable();
            $table->boolean('perfect_contact')->default(false);
            $table->string('phone')->nullable();
            $table->string('pintrest_url')->nullable();
            $table->string('pipeline_stage')->nullable();
            $table->boolean('pop_by')->default(false);
            $table->boolean('price_improvement_package')->default(false);
            $table->boolean('property_highlight_video')->default(false);
            $table->boolean('property_website_qr_code')->default(false);
            $table->string('print_qr_code_sheet')->nullable();
            $table->boolean('qr_code_sign_rider')->default(false);
            $table->text('random_notes')->nullable(); // Converted to TEXT
            $table->string('realtor_board')->nullable();
            $table->string('realtor_com_url')->nullable();
            $table->unsignedBigInteger('referred_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
