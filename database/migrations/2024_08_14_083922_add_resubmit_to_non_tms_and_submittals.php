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
        Schema::table('non_tms', function (Blueprint $table) {
            $table->text('resubmit_text')->nullable()->after('additionalFeesDescription');
        });

        Schema::table('submittals', function (Blueprint $table) {
            $table->text('resubmit_text')->nullable()->after('formType');
            $table->varchar('resubmitting_to_which_team', 191)->nullable()->after('resubmit_text');
            $table->text('resubmitting_why_list_all_changes')->nullable()->after('resubmitting_to_which_team');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('non_tms', function (Blueprint $table) {
            $table->dropColumn('resubmit_text');
        });

        Schema::table('submittals', function (Blueprint $table) {
            $table->dropColumn('resubmit_text');
            $table->dropColumn('resubmitting_to_which_team');
            $table->dropColumn('resubmitting_why_list_all_changes');
        });
    }
};
