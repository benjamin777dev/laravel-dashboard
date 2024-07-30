<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsAndPartnershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams_and_partnerships', function (Blueprint $table) {
            $table->id();
            $table->string('created_by')->nullable();
            $table->dateTime('created_date')->nullable();
            $table->string('currency')->nullable();
            $table->string('email')->nullable();
            $table->boolean('email_opt_out')->default(false);
            $table->decimal('exchange_rate', 8, 2)->nullable();
            $table->json('members')->nullable();
            $table->string('modified_by')->nullable();
            $table->string('secondary_email')->nullable();
            $table->string('tag')->nullable();
            $table->decimal('team_cap', 8, 2)->nullable();
            $table->string('team_created_by')->nullable();
            $table->string('team_lead')->nullable();
            $table->string('team_or_partner_image')->nullable();
            $table->string('team_or_partner_owner')->nullable();
            $table->string('team_profile')->nullable();
            $table->unsignedBigInteger('team_partnership_id')->nullable()->unique();
            $table->string('name')->nullable()->unique();
            $table->timestamps();
            $table->dateTime('last_activity_time')->nullable();
            $table->string('layout')->nullable();
            $table->dateTime('user_modified_time')->nullable();
            $table->dateTime('system_modified_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams_and_partnerships');
    }
}