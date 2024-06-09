<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('Secondary_Email')->nullable();
            $table->timestamp('Last_Activity_Time')->nullable();
            $table->string('Currency')->nullable();
            $table->float('Exchange_Rate', 10, 6)->nullable();
            $table->boolean('Email_Opt_Out')->default(false);
            $table->string('Layout')->nullable();
            $table->string('Tag')->nullable();
            $table->timestamp('User_Modified_Time')->nullable();
            $table->timestamp('System_Modified_Time')->nullable();
            $table->timestamp('User_Related_Activity_Time')->nullable();
            $table->timestamp('System_Related_Activity_Time')->nullable();
            $table->string('LAST_ACTION')->nullable();
            $table->timestamp('LAST_ACTION_TIME')->nullable();
            $table->timestamp('LAST_SENT_TIME')->nullable();
            $table->string('Unsubscribed_Mode')->nullable();
            $table->timestamp('Unsubscribed_Time')->nullable();
            $table->string('Record_Approval_Status')->nullable();
            $table->boolean('Is_Record_Duplicate')->default(false);
            $table->string('Record_Image')->nullable();
            $table->boolean('Locked__s')->default(false);
            $table->boolean('T')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn([
                'Secondary_Email',
                'Last_Activity_Time',
                'Currency',
                'Exchange_Rate',
                'Email_Opt_Out',
                'Layout',
                'Tag',
                'User_Modified_Time',
                'System_Modified_Time',
                'User_Related_Activity_Time',
                'System_Related_Activity_Time',
                'LAST_ACTION',
                'LAST_ACTION_TIME',
                'LAST_SENT_TIME',
                'Unsubscribed_Mode',
                'Unsubscribed_Time',
                'Record_Approval_Status',
                'Is_Record_Duplicate',
                'Record_Image',
                'Locked__s',
                'T',
            ]);
        });
    }
};
