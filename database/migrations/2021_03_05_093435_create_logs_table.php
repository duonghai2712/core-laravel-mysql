<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatelogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('user_name');
            $table->string('email');
            $table->string('action');
            $table->string('table');
            $table->bigInteger('record_id')->default(0);
            $table->bigInteger('project_id')->default(0);
            $table->text('query');

            $table->softDeletes();
            $table->timestamps();

            $table->index('id');
        });

        $this->updateTimestampDefaultValue('logs', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
