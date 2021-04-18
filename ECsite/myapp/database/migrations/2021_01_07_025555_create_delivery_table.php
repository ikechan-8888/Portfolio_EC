<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery', function (Blueprint $table) {
            $table->increments('id');
            $table->string('login_id', 256)->nullable(false);
            $table->string('delivery_name', 256)->nullable(false);
            $table->string('postal_code', 8)->nullable(false);
            $table->string('delivery_address', 256)->nullable(false);
            $table->unsignedInteger('tel')->nullable(false);
            $table->tinyInteger('use')->nullable(false);
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
        Schema::dropIfExists('delivery');
    }
}
