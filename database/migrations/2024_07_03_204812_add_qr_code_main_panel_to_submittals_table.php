<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQrCodeMainPanelToSubmittalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submittals', function (Blueprint $table) {
            $table->boolean('qrCodeMainPanel')->default(false)->after('formType');
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
            $table->dropColumn('qrCodeMainPanel');
        });
    }
}
