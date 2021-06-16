<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOrderIdStoreCrossDeviceCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_cross_device_collections', function (Blueprint $table) {
            $table->dropForeign('store_cross_device_collections_order_device_id_foreign');
            $table->dropColumn('order_device_id');

            $table->dropForeign('store_cross_device_collections_device_id_foreign');
            $table->dropColumn('device_id');

            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_cross_device_collections', function (Blueprint $table) {
            $table->unsignedBigInteger('order_device_id')->nullable();
            $table->foreign('order_device_id')->references('id')->on('order_devices')->onDelete('cascade');

            $table->unsignedBigInteger('device_id')->nullable();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');

            $table->dropForeign('store_cross_device_collections_order_id_foreign');
            $table->dropColumn('order_id');
        });

    }
}
