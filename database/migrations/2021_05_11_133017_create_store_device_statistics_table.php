<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatestoreDeviceStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_device_statistics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('device_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();

            $table->unsignedBigInteger('device_statistic_id')->nullable();
            $table->unsignedBigInteger('collection_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('rank_id')->nullable();

            $table->date('date_at');
            $table->unsignedInteger('second')->default(0);
            $table->unsignedInteger('total_time')->default(0);
            $table->unsignedInteger('number_time')->default(0);

            $table->timestamps();

            $table->foreign('device_statistic_id')->references('id')->on('device_statistics')->onDelete('cascade');
            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('rank_id')->references('id')->on('ranks')->onDelete('cascade');

            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        $this->updateTimestampDefaultValue('store_device_statistics', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_device_statistics');
    }
}
