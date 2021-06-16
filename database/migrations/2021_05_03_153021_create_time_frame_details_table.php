<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatetimeFrameDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_frame_details', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->date('date_at');

            $table->time('start_time');
            $table->time('end_time');

            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('time_frame_id')->nullable();

            $table->timestamps();

            $table->foreign('time_frame_id')->references('id')->on('time_frames')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        $this->updateTimestampDefaultValue('time_frame_details', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_frame_details');
    }
}
