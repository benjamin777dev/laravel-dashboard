<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->text('marketing_specialist')->nullable();
            $table->text('default_commission_plan_id')->nullable();
            $table->text('feature_cards_or_sheets')->nullable();
            $table->text('termination_reason')->nullable();
            $table->text('transaction_manager')->nullable();
            $table->text('auto_address')->nullable();
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
            $table->dropColumn([
                'marketing_specialist',
                'default_commission_plan_id',
                'feature_cards_or_sheets',
                'termination_reason',
                'transaction_manager',
                'auto_address'
            ]);
        });
    }
}
