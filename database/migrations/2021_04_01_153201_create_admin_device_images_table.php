<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateadminDeviceImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_device_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('position');
            $table->unsignedInteger('second');
            $table->unsignedInteger('type');

            $table->unsignedBigInteger('device_id')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();

            $table->timestamps();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        $this->updateTimestampDefaultValue('admin_device_images', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_device_images');
    }
}
