<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            // Adding new fields from Zoho response
            $table->string('Owner_Name')->nullable();
            $table->string('Owner_Id')->nullable();
            $table->string('Owner_Email')->nullable();
            $table->string('Modified_By_Name')->nullable();
            $table->string('Modified_By_Id')->nullable();
            $table->string('Modified_By_Email')->nullable();
            $table->string('Created_By_Name')->nullable();
            $table->string('Created_By_Id')->nullable();
            $table->string('Created_By_Email')->nullable();
            $table->string('Import_Code')->nullable();
            $table->integer('Display_Order')->nullable();
            $table->boolean('isD')->default(false);
            $table->boolean('Disable_Secondary_Access')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            // Dropping the new fields
            $table->dropColumn([
                'Owner_Name', 
                'Owner_Id', 
                'Owner_Email',
                'Modified_By_Name', 
                'Modified_By_Id', 
                'Modified_By_Email',
                'Created_By_Name', 
                'Created_By_Id', 
                'Created_By_Email',
                'Import_Code', 
                'Display_Order', 
                'isD', 
                'Disable_Secondary_Access'
            ]);
        });
    }
}
