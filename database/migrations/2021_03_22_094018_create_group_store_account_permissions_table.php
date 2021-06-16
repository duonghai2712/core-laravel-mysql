<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreategroupStoreAccountPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_store_account_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('permission_id')->nullable();
            $table->unsignedBigInteger('group_store_account_id')->nullable();
            $table->smallInteger('view')->default(0);
            $table->smallInteger('add')->default(0);
            $table->smallInteger('update')->default(0);
            $table->smallInteger('delete')->default(0);

            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('group_store_account_id')->references('id')->on('group_store_accounts')->onDelete('cascade');
        });

        $this->updateTimestampDefaultValue('group_store_account_permissions', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_store_account_permissions');
    }
}
