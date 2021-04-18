<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyproductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyproduct', function (Blueprint $table) {
            $table->increments('id');
            $table->string('login_id', 256)->nullable(false);
            $table->integer('product_id')->unsigned();
            $table->integer('buy_id')->unsigned();
            $table->string('item_size', 2)->nullable(false);
            $table->smallInteger('item_number')->unsigned()->nullable(false);
            $table->timestamp('updated_at')->useCurrent()->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();

            $table->foreign('product_id')->references('id')->on('product');
            $table->foreign('buy_id')->references('id')->on('buy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buyproduct');
    }
}
