<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBottomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bottoms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->tinyInteger('genre_status')->nullable(false);
            $table->unsignedInteger('ssize_waist')->nullable();
            $table->unsignedInteger('ssize_hips')->nullable();
            $table->unsignedInteger('ssize_rise')->nullable();
            $table->unsignedInteger('ssize_inseam')->nullable();
            $table->unsignedInteger('ssize_thigh')->nullable();
            $table->unsignedInteger('ssize_hem')->nullable();
            $table->unsignedInteger('msize_waist')->nullable();
            $table->unsignedInteger('msize_hips')->nullable();
            $table->unsignedInteger('msize_rise')->nullable();
            $table->unsignedInteger('msize_inseam')->nullable();
            $table->unsignedInteger('msize_thigh')->nullable();
            $table->unsignedInteger('msize_hem')->nullable();
            $table->unsignedInteger('lsize_waist')->nullable();
            $table->unsignedInteger('lsize_hips')->nullable();
            $table->unsignedInteger('lsize_rise')->nullable();
            $table->unsignedInteger('lsize_inseam')->nullable();
            $table->unsignedInteger('lsize_thigh')->nullable();
            $table->unsignedInteger('lsize_hem')->nullable();
            $table->unsignedInteger('xlsize_waist')->nullable();
            $table->unsignedInteger('xlsize_hips')->nullable();
            $table->unsignedInteger('xlsize_rise')->nullable();
            $table->unsignedInteger('xlsize_inseam')->nullable();
            $table->unsignedInteger('xlsize_thigh')->nullable();
            $table->unsignedInteger('xlsize_hem')->nullable();
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
        Schema::dropIfExists('bottoms');
    }
}
