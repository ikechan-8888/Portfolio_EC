<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tops', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->tinyInteger('genre_status')->nullable(false);
            $table->unsignedInteger('ssize_body')->nullable();
            $table->unsignedInteger('ssize_shoulder')->nullable();
            $table->unsignedInteger('ssize_length')->nullable();
            $table->unsignedInteger('ssize_sleeve')->nullable();
            $table->unsignedInteger('msize_body')->nullable();
            $table->unsignedInteger('msize_shoulder')->nullable();
            $table->unsignedInteger('msize_length')->nullable();
            $table->unsignedInteger('msize_sleeve')->nullable();
            $table->unsignedInteger('lsize_body')->nullable();
            $table->unsignedInteger('lsize_shoulder')->nullable();
            $table->unsignedInteger('lsize_length')->nullable();
            $table->unsignedInteger('lsize_sleeve')->nullable();
            $table->unsignedInteger('xlsize_body')->nullable();
            $table->unsignedInteger('xlsize_shoulder')->nullable();
            $table->unsignedInteger('xlsize_length')->nullable();
            $table->unsignedInteger('xlsize_sleeve')->nullable();
            $table->timestamp('updated_at')->useCurrent()->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();

            $table->foreign('product_id')->references('id')->on('product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tops');
    }
}
