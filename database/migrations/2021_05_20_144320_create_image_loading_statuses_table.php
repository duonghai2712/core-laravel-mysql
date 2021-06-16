<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateimageLoadingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_loading_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('device_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();

            $table->unsignedBigInteger('device_loading_status_id')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('status')->default(1);
            $table->unsignedBigInteger('type')->default(1);
            $table->time('time_at');
            $table->date('date_at');

            $table->timestamps();

            $table->foreign('device_loading_status_id')->references('id')->on('device_loading_statuses')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        $this->updateTimestampDefaultValue('image_loading_statuses', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_loading_statuses');
    }
}
