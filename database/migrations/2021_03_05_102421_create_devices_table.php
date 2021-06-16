<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatedevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('description')->nullable();
            $table->string('device_code')->unique()->nullable();
            $table->unsignedInteger('own')->default(1);
            $table->unsignedInteger('is_active')->default(1);
            $table->unsignedInteger('status')->default(1);
            $table->string('active_code')->nullable();
            $table->string('device_token')->nullable();


            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            $table->index(['id', 'deleted_at']);
            $table->index(['name', 'deleted_at']);
        });

        $this->updateTimestampDefaultValue('images', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
