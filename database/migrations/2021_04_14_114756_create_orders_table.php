<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('payment')->default(0);
            $table->unsignedInteger('status')->default(1);
            $table->string('code')->nullable();
            $table->string('note')->nullable();

            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('store_account_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('store_account_id')->references('id')->on('store_accounts')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            $table->index(['id', 'deleted_at']);
        });

        $this->updateTimestampDefaultValue('orders', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
