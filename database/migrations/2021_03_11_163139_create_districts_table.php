<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatedistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->smallInteger('id_api');
            $table->unsignedBigInteger('province_id')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');

        });

        $this->updateTimestampDefaultValue('districts', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('districts');
    }
}
