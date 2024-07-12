<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoOpAgentFieldsToDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->boolean('coOpAgentCHRFit')->nullable();
            $table->boolean('coOpAgentEBLetterSent')->nullable();
            $table->string('coOpAgentCompany')->nullable();
            $table->string('coOpAgentEmail')->nullable();
            $table->string('coOpAgentFirstName')->nullable();
            $table->string('coOpAgentLastName')->nullable();
            $table->string('coOpAgentPhone')->nullable();
            $table->string('teamPartnership')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn([
                'coOpAgentCHRFit',
                'coOpAgentEBLetterSent',
                'coOpAgentCompany',
                'coOpAgentEmail',
                'coOpAgentFirstName',
                'coOpAgentLastName',
                'coOpAgentPhone',
                'teamPartnership'
            ]);
        });
    }
}
