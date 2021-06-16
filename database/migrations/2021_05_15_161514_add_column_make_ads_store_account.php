<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMakeAdsStoreAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('make_ads')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_accounts', function (Blueprint $table) {
            $table->dropColumn('make_ads');

        });

    }
}
