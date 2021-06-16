<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateorderDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_devices', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedFloat('point')->default(0);
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->unsignedBigInteger('order_branch_id')->nullable();
            $table->unsignedBigInteger('order_store_id')->nullable();
            $table->unsignedBigInteger('store_account_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();

            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->foreign('store_account_id')->references('id')->on('store_accounts')->onDelete('cascade');
            $table->foreign('order_branch_id')->references('id')->on('order_branches')->onDelete('cascade');
            $table->foreign('order_store_id')->references('id')->on('order_stores')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        $this->updateTimestampDefaultValue('order_devices', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_devices');
    }
}
