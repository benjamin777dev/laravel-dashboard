<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('country')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->nullable();
            $table->text('zip')->nullable();
            $table->text('street')->nullable();
            $table->text('language')->nullable();
            $table->text('locale')->nullable();
            $table->boolean('is_online')->default(false);
            $table->text('currency')->nullable();
            $table->text('time_format')->nullable();
            $table->text('profile_name')->nullable();
            $table->text('mobile')->nullable();
            $table->text('time_zone')->nullable();
            $table->timestamp('created_time')->nullable();
            $table->timestamp('modified_time')->nullable();
            $table->boolean('confirmed')->default(false);
            $table->text('full_name')->nullable();
            $table->text('date_format')->nullable();
            $table->text('status')->nullable();
            $table->text('website')->nullable();
            $table->text('email_blast_opt_in')->nullable();
            $table->text('strategy_group')->nullable();
            $table->text('notepad_mailer_opt_in')->nullable();
            $table->text('market_mailer_opt_in')->nullable();
            $table->text('role_name')->nullable();
            $table->text('role_id')->nullable();
            $table->text('modified_by_name')->nullable();
            $table->text('modified_by_id')->nullable();
            $table->text('created_by_name')->nullable();
            $table->text('created_by_id')->nullable();
            $table->text('alias')->nullable();
            $table->text('fax')->nullable();
            $table->text('country_locale')->nullable();
            $table->boolean('sandbox_developer')->default(false);
            $table->boolean('microsoft')->default(false);
            $table->text('reporting_to')->nullable();
            $table->text('offset')->nullable();
            $table->text('next_shift')->nullable();
            $table->text('shift_effective_from')->nullable();
            $table->boolean('transaction_status_reports')->default(false);
            $table->text('joined_date')->nullable();
            $table->json('territories')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'country', 'city', 'state', 'zip', 'street', 'language', 
                'locale', 'is_online', 'currency', 'time_format', 'profile_name', 
                'mobile', 'time_zone', 'created_time', 'modified_time', 'confirmed', 
                'full_name', 'date_format', 'status', 'website', 'email_blast_opt_in',
                'strategy_group', 'notepad_mailer_opt_in', 'market_mailer_opt_in',
                'role_name', 'role_id', 'modified_by_name', 'modified_by_id',
                'created_by_name', 'created_by_id', 'alias', 'fax', 'country_locale',
                'sandbox_developer', 'microsoft', 'reporting_to', 'offset', 'next_shift',
                'shift_effective_from', 'transaction_status_reports', 'joined_date',
                'territories'
            ]);
        });
    }
}
