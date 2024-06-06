<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Add columns only if they don't already exist
            if (!Schema::hasColumn('contacts', 'email_blast_opt_in')) {
                $table->boolean('email_blast_opt_in')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'twitter_url')) {
                $table->text('twitter_url')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'emergency_contact_phone')) {
                $table->text('emergency_contact_phone')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'print_qr_code_sheet')) {
                $table->boolean('print_qr_code_sheet')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'invalid_address_usps')) {
                $table->boolean('invalid_address_usps')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'mls_recolorado')) {
                $table->boolean('mls_recolorado')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'mls_navica')) {
                $table->boolean('mls_navica')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'perfect')) {
                $table->boolean('perfect')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'realtor_board')) {
                $table->text('realtor_board')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'initial_split')) {
                $table->text('initial_split')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'has_missing_important_date')) {
                $table->boolean('has_missing_important_date')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'need_o_e')) {
                $table->boolean('need_o_e')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'culture_index')) {
                $table->text('culture_index')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'sticky_dots')) {
                $table->text('sticky_dots')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'strategy_group')) {
                $table->text('strategy_group')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'weekly_email')) {
                $table->boolean('weekly_email')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'number_of_chats')) {
                $table->text('number_of_chats')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'notepad_mailer_opt_in')) {
                $table->boolean('notepad_mailer_opt_in')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'chr_gives_amount')) {
                $table->text('chr_gives_amount')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'other_zip')) {
                $table->text('other_zip')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'market_mailer_opt_in')) {
                $table->boolean('market_mailer_opt_in')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'groups')) {
                $table->text('groups')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'closer_name_phone')) {
                $table->text('closer_name_phone')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'unsubscribe_from_reviews')) {
                $table->boolean('unsubscribe_from_reviews')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'outsourced_mktg_onsite_video')) {
                $table->boolean('outsourced_mktg_onsite_video')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'random_notes')) {
                $table->text('random_notes')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'residual_cap')) {
                $table->text('residual_cap')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'email_blast_to_reverse_prospect_list')) {
                $table->boolean('email_blast_to_reverse_prospect_list')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'review_generation')) {
                $table->boolean('review_generation')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'zillow_url')) {
                $table->text('zillow_url')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'agent_assistant')) {
                $table->text('agent_assistant')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'social_media_ads')) {
                $table->boolean('social_media_ads')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'referred_by')) {
                $table->text('referred_by')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'peer_advisor')) {
                $table->text('peer_advisor')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'agent_name_on_marketing')) {
                $table->text('agent_name_on_marketing')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'other_street')) {
                $table->text('other_street')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'qr_code_sign_rider')) {
                $table->boolean('qr_code_sign_rider')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'google_business_page_url')) {
                $table->text('google_business_page_url')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'has_email')) {
                $table->boolean('has_email')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'salesforce_id')) {
                $table->text('salesforce_id')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'mls_ires')) {
                $table->boolean('mls_ires')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'outsourced_mktg_floorplans')) {
                $table->boolean('outsourced_mktg_floorplans')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'income_goal')) {
                $table->text('income_goal')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'chr_relationship')) {
                $table->text('chr_relationship')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'locked_s')) {
                $table->boolean('locked_s')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'tag')) {
                $table->text('tag')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'import_batch')) {
                $table->text('import_batch')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'termination_date')) {
                $table->timestamp('termination_date')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'license_start_date')) {
                $table->timestamp('license_start_date')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'brokermint_id')) {
                $table->text('brokermint_id')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'residual_split')) {
                $table->text('residual_split')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'visitor_score')) {
                $table->text('visitor_score')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'sign_vendor')) {
                $table->text('sign_vendor')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'other_state')) {
                $table->text('other_state')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'last_activity_time')) {
                $table->timestamp('last_activity_time')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'unsubscribed_mode')) {
                $table->text('unsubscribed_mode')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'license_number')) {
                $table->text('license_number')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'exchange_rate')) {
                $table->text('exchange_rate')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'lead_source_detail')) {
                $table->text('lead_source_detail')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'email_to_cc_on_all_marketing_comms')) {
                $table->text('email_to_cc_on_all_marketing_comms')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'tm_preference')) {
                $table->text('tm_preference')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'salutation_s')) {
                $table->text('salutation_s')->nullable();
            }
            if (!Schema::hasColumn('contacts', '$locked_for_me')) {
                $table->boolean('$locked_for_me')->nullable();
            }
            if (!Schema::hasColumn('contacts', '$approved')) {
                $table->boolean('$approved')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'email_cc_1')) {
                $table->text('email_cc_1')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'google_business')) {
                $table->text('google_business')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'email_cc_2')) {
                $table->text('email_cc_2')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'days_visited')) {
                $table->text('days_visited')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'pipeline_stage')) {
                $table->text('pipeline_stage')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'social_media_images')) {
                $table->boolean('social_media_images')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'fees_charged_to_seller_at_closing')) {
                $table->text('fees_charged_to_seller_at_closing')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'realtor_com_url')) {
                $table->text('realtor_com_url')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'title_company')) {
                $table->text('title_company')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'select_your_prints')) {
                $table->text('select_your_prints')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'role')) {
                $table->text('role')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'business_name')) {
                $table->text('business_name')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'missing')) {
                $table->text('missing')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'groups_tags')) {
                $table->text('groups_tags')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'lender_company_name')) {
                $table->text('lender_company_name')->nullable();
            }
            if (!Schema::hasColumn('contacts', '$zia_owner_assignment')) {
                $table->text('$zia_owner_assignment')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'secondary_email')) {
                $table->text('secondary_email')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'current_annual_academy')) {
                $table->boolean('current_annual_academy')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'transaction_status_reports')) {
                $table->boolean('transaction_status_reports')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'non_tm_assignment')) {
                $table->text('non_tm_assignment')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'user')) {
                $table->text('user')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'lender_email')) {
                $table->text('lender_email')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'sign_install')) {
                $table->boolean('sign_install')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'team_name')) {
                $table->text('team_name')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'pintrest_url')) {
                $table->text('pintrest_url')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'youtube_url')) {
                $table->text('youtube_url')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'include_insights_in_intro')) {
                $table->boolean('include_insights_in_intro')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'import_id')) {
                $table->text('import_id')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'business_info')) {
                $table->text('business_info')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'email_signature')) {
                $table->text('email_signature')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'phone')) {
                $table->text('phone')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'property_website_qr_code')) {
                $table->boolean('property_website_qr_code')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'draft_showing_instructions')) {
                $table->boolean('draft_showing_instructions')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'additional_email_for_confirmation')) {
                $table->text('additional_email_for_confirmation')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'important_date_added')) {
                $table->boolean('important_date_added')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'emergency_contact_name')) {
                $table->text('emergency_contact_name')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'market_area')) {
                $table->text('market_area')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'initial_cap')) {
                $table->text('initial_cap')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'unsubscribed_time')) {
                $table->boolean('unsubscribed_time')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'mls_ppar')) {
                $table->boolean('mls_ppar')->nullable();
            }
            if (!Schema::hasColumn('contacts', 'outsourced_mktg_3d_zillow_tour')) {
                $table->boolean('outsourced_mktg_3d_zillow_tour')->nullable();
            }
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Drop the columns if needed
            $table->dropColumn([
                'email_blast_opt_in',
                'twitter_url',
                'emergency_contact_phone',
                'print_qr_code_sheet',
                'invalid_address_usps',
                'mls_recolorado',
                'mls_navica',
                'perfect',
                'realtor_board',
                'initial_split',
                'has_missing_important_date',
                'need_o_e',
                'culture_index',
                'sticky_dots',
                'strategy_group',
                'weekly_email',
                'number_of_chats',
                'notepad_mailer_opt_in',
                'chr_gives_amount',
                'other_zip',
                'market_mailer_opt_in',
                'groups',
                'closer_name_phone',
                'unsubscribe_from_reviews',
                'outsourced_mktg_onsite_video',
                'random_notes',
                'residual_cap',
                'email_blast_to_reverse_prospect_list',
                'review_generation',
                'zillow_url',
                'agent_assistant',
                'social_media_ads',
                'referred_by',
                'peer_advisor',
                'agent_name_on_marketing',
                'other_street',
                'qr_code_sign_rider',
                'google_business_page_url',
                'has_email',
                'salesforce_id',
                'mls_ires',
                'outsourced_mktg_floorplans',
                'income_goal',
                'chr_relationship',
                'locked_s',
                'tag',
                'import_batch',
                'termination_date',
                'license_start_date',
                'brokermint_id',
                'residual_split',
                'visitor_score',
                'sign_vendor',
                'other_state',
                'last_activity_time',
                'unsubscribed_mode',
                'license_number',
                'exchange_rate',
                'lead_source_detail',
                'email_to_cc_on_all_marketing_comms',
                'tm_preference',
                'salutation_s',
                '$locked_for_me',
                '$approved',
                'email_cc_1',
                'google_business',
                'email_cc_2',
                'days_visited',
                'pipeline_stage',
                'social_media_images',
                'fees_charged_to_seller_at_closing',
                'realtor_com_url',
                'title_company',
                'select_your_prints',
                'role',
                'business_name',
                'missing',
                'groups_tags',
                'lender_company_name',
                '$zia_owner_assignment',
                'secondary_email',
                'current_annual_academy',
                'transaction_status_reports',
                'non_tm_assignment',
                'user',
                'lender_email',
                'sign_install',
                'team_name',
                'pintrest_url',
                'youtube_url',
                'include_insights_in_intro',
                'import_id',
                'business_info',
                'email_signature',
                'phone',
                'property_website_qr_code',
                'draft_showing_instructions',
                'additional_email_for_confirmation',
                'important_date_added',
                'emergency_contact_name',
                'market_area',
                'initial_cap',
                'unsubscribed_time',
                'mls_ppar',
                'outsourced_mktg_3d_zillow_tour'
            ]);
        });
    }
}
