<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submittals', function (Blueprint $table) {
            $table->text('paragraph_200_words_4_page_brochure_or_look_book')->nullable();
            $table->text('buyer_agent_compensation_offering')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submittals', function (Blueprint $table) {
            $table->dropColumn('paragraph_200_words_4_page_brochure_or_look_book');
            $table->dropColumn('buyer_agent_compensation_offering');
        });
    }
};
