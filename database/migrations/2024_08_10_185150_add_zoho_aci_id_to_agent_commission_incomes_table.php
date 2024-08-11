<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZohoAciIdToAgentCommissionIncomesTable extends Migration
{
    public function up()
    {
        Schema::table('agent_commission_incomes', function (Blueprint $table) {
            $table->unsignedBigInteger('zoho_aci_id')->nullable()->after('id');
            $table->unique('zoho_aci_id'); // Ensure no duplicates
        });
    }

    public function down()
    {
        Schema::table('agent_commission_incomes', function (Blueprint $table) {
            $table->dropUnique(['zoho_aci_id']);
            $table->dropColumn('zoho_aci_id');
        });
    }
}
