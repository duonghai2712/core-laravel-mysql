<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatelogPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_points', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('type')->default(1);
            $table->unsignedInteger('point')->default(0);
            $table->unsignedInteger('time')->default(0);

            $table->string('code')->nullable();
            $table->string('transaction')->nullable();

            $table->date('start_date');
            $table->date('end_date');

            $table->time('start_time');
            $table->time('end_time');


            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();

            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        $this->updateTimestampDefaultValue('log_points', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_points');
    }
}
