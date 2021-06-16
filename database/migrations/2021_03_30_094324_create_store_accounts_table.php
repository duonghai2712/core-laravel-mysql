<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatestoreAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('representative');
            $table->string('email');
            $table->smallInteger('role')->default(1);
            $table->string('phone_number')->nullable();
            $table->smallInteger('is_active')->default(1);

            $table->string('username');
            $table->string('password', 60);
            $table->string('language')->default('');

            $table->string('api_access_token')->default('');

            $table->smallInteger('is_send_email')->default(0)->comment('0: Not send email, 1: Send email');
            $table->unsignedBigInteger('group_store_account_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('profile_collection_id')->nullable();


            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('profile_collection_id')->references('id')->on('collections')->onDelete('cascade');
            $table->foreign('group_store_account_id')->references('id')->on('group_store_accounts')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            $table->index(['id', 'deleted_at']);

        });

        $this->updateTimestampDefaultValue('store_accounts', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_accounts');
    }
}
