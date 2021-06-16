<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateaccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('email');
            $table->string('password', 60);

            $table->string('language')->default('');

            $table->bigInteger('last_notification_id')->default(0);

            $table->string('api_access_token')->default('');

            $table->smallInteger('rule')->default(1);

            $table->smallInteger('is_active')->default(1);
            $table->string('username');

            $table->unsignedBigInteger('project_id')->nullable();
            $table->smallInteger('is_send_email')->default(0)->comment('0: Not send email, 1: Send email');

            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            $table->index(['id', 'deleted_at']);
        });

        $this->updateTimestampDefaultValue('accounts', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
