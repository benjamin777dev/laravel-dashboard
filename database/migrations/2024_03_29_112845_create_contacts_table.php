<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatecontactsTable extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('contact_owner')->nullable();
            $table->string('zoho_contact_id')->nullable();
            $table->string('email')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('created_time')->nullable();
            $table->string('abcd')->nullable();
            $table->string('mailing_address')->nullable();
            $table->string('mailing_city')->nullable();
            $table->string('relationship_type')->nullable();
            $table->string('market_area')->nullable();
            $table->string('envelope_salutation')->nullable();
            $table->string('mailing_state')->nullable();
            $table->string('mailing_zip')->nullable();
            $table->boolean('isContactCompleted')->nullable()->default(1);
            $table->boolean('isInZoho')->nullable()->default(1);            
            $table->string('mobile')->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_information')->nullable();
            $table->string('secondory_email')->nullable();
            $table->string('Lead_Source')->nullable();
            $table->string('group_id')->nullable();
            $table->string('referred_id')->nullable();
            $table->string('lead_source_detail')->nullable();
            $table->string('spouse_partner')->nullable();
            $table->timestamp('last_called')->nullable();
            $table->timestamp('last_emailed')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
