<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteColumnStartTimeLogPoint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_points', function (Blueprint $table) {
            $table->dropColumn('point');
            $table->dropColumn('start_time');
            $table->dropColumn('time');
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
        Schema::table('log_points', function (Blueprint $table) {
            $table->time('start_time')->nullable();
            $table->unsignedInteger('point')->default(0);
            $table->unsignedInteger('time')->default(0);
            $table->dropForeign('log_points_order_id_foreign');
            $table->dropColumn('order_id');
        });

    }
}
