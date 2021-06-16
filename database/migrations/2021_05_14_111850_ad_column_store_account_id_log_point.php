<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdColumnStoreAccountIdLogPoint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_points', function (Blueprint $table) {
            $table->unsignedBigInteger('store_account_id')->nullable();
            $table->foreign('store_account_id')->references('id')->on('store_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_points', function (Blueprint $table) {
            $table->dropForeign('time_frames_store_account_id_foreign');
            $table->dropColumn('store_account_id');
        });

    }
}
