<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToContactGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_groups', function (Blueprint $table) {
            // Fields for JSON data
            $table->timestamp('modified_time')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('created_time')->nullable();
            $table->string('name')->nullable();
            $table->timestamp('last_activity_time')->nullable();
            $table->string('import_batch')->nullable();
            $table->string('secondary_email')->nullable();
            $table->boolean('email_opt_out')->default(false);

            // Fields for IDs and Names
            $table->bigInteger('modified_by_id')->unsigned()->nullable();
            $table->string('modified_by_name')->nullable();
            $table->bigInteger('created_by_id')->unsigned()->nullable();
            $table->string('created_by_name')->nullable();
            $table->bigInteger('contacts_id')->unsigned()->nullable();
            $table->string('contacts_name')->nullable();
            $table->bigInteger('groups_id')->unsigned()->nullable();
            $table->string('groups_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_groups', function (Blueprint $table) {
            $table->dropColumn('modified_time');
            $table->dropColumn('email');
            $table->dropColumn('created_time');
            $table->dropColumn('name');
            $table->dropColumn('last_activity_time');
            $table->dropColumn('import_batch');
            $table->dropColumn('secondary_email');
            $table->dropColumn('email_opt_out');
            $table->dropColumn('modified_by_id');
            $table->dropColumn('modified_by_name');
            $table->dropColumn('created_by_id');
            $table->dropColumn('created_by_name');
            $table->dropColumn('contacts_id');
            $table->dropColumn('contacts_name');
            $table->dropColumn('groups_id');
            $table->dropColumn('groups_name');
        });
    }
}
