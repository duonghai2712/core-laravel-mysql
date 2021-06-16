<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateprojectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->smallInteger('is_active')->default(1)->comment('0: Disable, 1: Active');
            $table->string('description')->nullable();

            // Add some more columns

            $table->softDeletes();
            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('projects', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
