<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateranksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('description')->nullable();
            $table->smallInteger('coefficient');


            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

        });

        $this->updateTimestampDefaultValue('ranks', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ranks');
    }
}
