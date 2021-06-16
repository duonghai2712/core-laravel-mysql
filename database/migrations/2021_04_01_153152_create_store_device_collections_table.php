<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatestoreDeviceCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_device_collections', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('position');
            $table->unsignedInteger('second');
            $table->unsignedInteger('type');

            $table->unsignedBigInteger('device_id')->nullable();
            $table->unsignedBigInteger('collection_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('store_account_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();

            $table->timestamps();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('store_account_id')->references('id')->on('store_accounts')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });

        $this->updateTimestampDefaultValue('store_device_collections', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_device_collections');
    }
}
