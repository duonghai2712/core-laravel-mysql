<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateimagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->nullable();
            $table->string('md5_file')->default('');
                        $table->string('source_thumb');
            $table->string('source');
            $table->unsignedBigInteger('project_id')->nullable();

            $table->unsignedBigInteger('file_size')->default(0);
            $table->unsignedInteger('width')->default(0);
            $table->tinyInteger('type');
            $table->unsignedInteger('height')->default(0);

            $table->unsignedBigInteger('account_id')->nullable();
            $table->string('mimes');
            $table->string('duration')->nullable();
            $table->string('dimension')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');

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
        Schema::dropIfExists('images');
    }
}
