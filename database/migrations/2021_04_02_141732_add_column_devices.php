<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDevices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->unsignedInteger('total_time_admin')->default(0);
            $table->unsignedInteger('total_time_store')->default(0);
            $table->unsignedInteger('block_ads')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('total_time_admin');
            $table->dropColumn('total_time_store');
            $table->dropColumn('block_ads');
        });

    }
}
