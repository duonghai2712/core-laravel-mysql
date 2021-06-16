<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreateresetPasswordAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reset_password_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->index();
            $table->string('token')->nullable();
            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('reset_password_accounts', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reset_password_accounts');
    }
}
