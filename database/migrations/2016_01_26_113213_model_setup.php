<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModelSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('products', function($collection) {
            $collection->index('_id');
            $collection->index('user_id');
            $collection->index(['location' => '2dsphere']);
            $collection->index('created_at');
        });

        Schema::create('chats', function($collection) {
            $collection->index('_id');
            $collection->index('seller_id');
            $collection->index('buyer_id');
            $collection->index('created_at');
        });

        Schema::create('users', function ($collection) {
            $collection->index('_id');
            $collection->index(['location' => '2dsphere']);
            $collection->index('created_at');
        });

        Schema::create('user_accounts', function($collection) {
            $collection->index('_id');
            $collection->index('user_id');
            $collection->index('provider');
            $collection->index('token');
        });

        Schema::connection('mysql')->create('reports', function($table) {
            $table->increments('id');
            $table->string('product_id');
            $table->string('reason');
            $table->integer('num_reports');
            $table->timestamps();
        });

        Schema::connection('mysql')->create('categories', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->drop('categories');
        Schema::connection('mysql')->drop('reports');
        Schema::drop('user_accounts');
        Schema::drop('users');
        Schema::drop('chats');
        Schema::drop('products');
    }
}
