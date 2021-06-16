<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatecollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->nullable();
            $table->string('md5_file')->default('');
            $table->string('source_thumb');
            $table->string('source');
            $table->unsignedBigInteger('project_id')->nullable();

            $table->unsignedBigInteger('file_size')->default(0);
            $table->unsignedInteger('width')->default(0);
            $table->unsignedInteger('level')->default(0);
            $table->tinyInteger('type');
            $table->unsignedInteger('height')->default(0);

            $table->unsignedBigInteger('store_account_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('mimes');
            $table->string('duration')->nullable();
            $table->string('dimension')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('store_account_id')->references('id')->on('store_accounts')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            $table->index(['id', 'deleted_at']);
            $table->index(['name', 'deleted_at']);
        });

        $this->updateTimestampDefaultValue('collections', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collections');
    }
}
