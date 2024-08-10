<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('agent_commission_incomes', function (Blueprint $table) {
            $table->id();
            $table->string('adjusted_gross_commission')->nullable();
            $table->decimal('admin_fee_income', 15, 2)->nullable();
            $table->decimal('after_splits', 15, 2)->nullable();
            $table->decimal('agent_check_amount', 15, 2)->nullable();
            $table->string('record_image')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->decimal('agent_contribution_to_client_transaction_costs', 15, 2)->nullable();
            $table->string('name');
            $table->decimal('agent_portion_of_commission_that_gets_split', 15, 2)->nullable();
            $table->string('agent_team_for_capping')->nullable();
            $table->unsignedBigInteger('chr_agent_id')->nullable();
            $table->integer('calculated_count')->nullable();
            $table->decimal('calculated_gci', 15, 2)->nullable();
            $table->decimal('calculated_volume', 15, 2)->nullable();
            $table->decimal('chr_gives', 15, 2)->nullable();
            $table->decimal('chr_gives_due_to_chr', 15, 2)->nullable();
            $table->date('closing_date')->nullable();
            $table->decimal('colorado_home_realty', 15, 2)->nullable();
            $table->string('commission_notes')->nullable();
            $table->decimal('commission_percent', 5, 2)->nullable();
            $table->string('created_by')->nullable();
            $table->decimal('credit_to_client', 15, 2)->nullable();
            $table->string('currency')->nullable();
            $table->integer('current_year')->nullable();
            $table->boolean('double_ended')->nullable();
            $table->decimal('ecommission', 15, 2)->nullable();
            $table->decimal('ecommission_payout', 15, 2)->nullable();
            $table->string('email')->nullable();
            $table->boolean('email_opt_out')->nullable();
            $table->decimal('exchange_rate', 8, 4)->nullable();
            $table->boolean('gt')->nullable();
            $table->decimal('home_warranty', 15, 2)->nullable();
            $table->decimal('home_warranty_payout', 15, 2)->nullable();
            $table->string('import_batch_id')->nullable();
            $table->decimal('irs_reported_1099_income_for_this_transaction', 15, 2)->nullable();
            $table->decimal('less_initial_split_to_chr', 15, 2)->nullable();
            $table->decimal('less_residual_split_to_chr', 15, 2)->nullable();
            $table->decimal('less_split_to_chr', 15, 2)->nullable();
            $table->decimal('mentee_amount_paid', 15, 2)->nullable();
            $table->string('modified_by')->nullable();
            $table->boolean('np1')->nullable();
            $table->decimal('past_due_amount_to_chr', 15, 2)->nullable();
            $table->boolean('personal_transaction')->nullable();
            $table->decimal('portion_of_total', 5, 2)->nullable();
            $table->string('representing')->nullable();
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->string('secondary_email')->nullable();
            $table->decimal('sides', 5, 2)->nullable();
            $table->decimal('split_percent', 5, 2)->nullable();
            $table->decimal('split_to_chr', 5, 2)->nullable();
            $table->string('stage')->nullable();
            $table->string('strategy_group')->nullable();
            $table->decimal('sub_total_after_expenses', 15, 2)->nullable();
            $table->string('tag')->nullable();
            $table->unsignedBigInteger('team_partnership_id')->nullable();
            $table->decimal('tm_fees_due_to_chr', 15, 2)->nullable();
            $table->decimal('total_gross_commission', 15, 2)->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->timestamps();
            
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chr_agent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('team_partnership_id')->references('id')->on('teams_and_partnerships')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('deals')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('agent_commission_incomes');
    }
};
