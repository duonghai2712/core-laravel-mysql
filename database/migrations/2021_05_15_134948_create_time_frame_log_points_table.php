<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatetimeFrameLogPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_frame_log_points', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('log_point_id')->nullable();
            $table->unsignedBigInteger('time_frame_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();

            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('log_point_id')->references('id')->on('log_points')->onDelete('cascade');
            $table->foreign('time_frame_id')->references('id')->on('time_frames')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        $this->updateTimestampDefaultValue('time_frame_log_points', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_frame_log_points');
    }
}
