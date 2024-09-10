<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealStageHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_stage_histories', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->string('zoho_deal_id', 191);  // Foreign key referencing 'zoho_deal_id' in the deals table
            $table->foreign('zoho_deal_id')->references('zoho_deal_id')->on('deals')->onDelete('cascade');
            $table->string('zoho_id')->unique();  // This is the 'Id' field in the CSV (unique ID for each stage history record)
            $table->string('stage');
            $table->timestamp('modified_time')->nullable();
            $table->integer('stage_duration')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->date('closing_date')->nullable();
            $table->string('currency', 10)->default('USD');
            $table->decimal('exchange_rate', 10, 2)->default(1.00);
            $table->decimal('expected_revenue', 15, 2)->nullable();
            $table->timestamp('last_activity_time')->nullable();
            $table->string('moved_to')->nullable();
            $table->integer('probability')->nullable();
            $table->timestamps();  // Adds created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deal_stage_histories');
    }
}
