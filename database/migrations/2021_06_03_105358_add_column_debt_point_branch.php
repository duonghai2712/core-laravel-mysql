<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDebtPointBranch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->unsignedFloat('debt_point')->default(0);
            $table->unsignedBigInteger('make_ads')->default(1);

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
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('debt_point');
            $table->dropColumn('make_ads');

            $table->dropForeign('branches_store_account_id_foreign');
            $table->dropColumn('store_account_id');
        });

    }
}
