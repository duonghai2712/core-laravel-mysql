<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCollectionId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_accounts', function (Blueprint $table) {
            $table->dropForeign('store_accounts_store_image_id_foreign');
            $table->dropColumn('store_image_id');

            $table->unsignedBigInteger('profile_collection_id')->nullable();
            $table->foreign('profile_collection_id')->references('id')->on('collections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_accounts', function (Blueprint $table) {
            $table->dropForeign('store_accounts_profile_collection_id_foreign');
            $table->dropColumn('profile_collection_id');

            $table->unsignedBigInteger('store_image_id')->nullable();
            $table->foreign('store_image_id')->references('id')->on('images')->onDelete('cascade');
        });

    }
}
