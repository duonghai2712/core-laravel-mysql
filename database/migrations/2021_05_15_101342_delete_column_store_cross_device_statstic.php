<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteColumnStoreCrossDeviceStatstic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_cross_device_statistics', function (Blueprint $table) {
            $table->dropForeign('store_cross_device_statistics_store_cross_id_foreign');
            $table->dropColumn('store_cross_id');

            $table->dropForeign('store_cross_device_statistics_branch_cross_id_foreign');
            $table->dropColumn('branch_cross_id');

            $table->dropForeign('store_cross_device_statistics_rank_cross_id_foreign');
            $table->dropColumn('rank_cross_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_cross_device_statistics', function (Blueprint $table) {
        });

    }
}
