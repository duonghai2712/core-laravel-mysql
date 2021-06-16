<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBlockTimeInOrderDevice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_devices', function (Blueprint $table) {
            $table->unsignedBigInteger('block_time')->default(0);
            $table->unsignedBigInteger('total_time_store')->default(0);
            $table->unsignedBigInteger('total_time_admin')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_devices', function (Blueprint $table) {
            $table->dropColumn('block_time');
            $table->dropColumn('total_time_store');
            $table->dropColumn('total_time_admin');
        });

    }
}
