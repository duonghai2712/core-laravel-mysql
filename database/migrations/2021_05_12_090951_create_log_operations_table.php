<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatelogOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_operations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('store_account_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();

            $table->string('name');
            $table->string('description')->nullable();

            $table->timestamps();
            $table->foreign('store_account_id')->references('id')->on('store_accounts')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        $this->updateTimestampDefaultValue('log_operations', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_operations');
    }
}
