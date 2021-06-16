<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatecustomerAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Add some more columns
            $table->softDeletes();
            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('customer_accounts', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_accounts');
    }
}
