<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyRenameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_rename', function (Blueprint $table) {
            $table->increments('id');
            $table->string('login_id', 256)->nullable(false);
            $table->unsignedInteger('buy_price')->nullable(false);
            $table->string('buy_name', 256)->nullable(false);
            $table->string('postal_code', 8)->nullable(false);
            $table->string('delivery_address', 256)->nullable(false);
            $table->unsignedBigInteger('tel')->nullable(false);
            $table->timestamp('updated_at')->useCurrent()->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buy_rename');
    }
}
