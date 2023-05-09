<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->integer('store_id');
            $table->integer('user_id');
            $table->enum('type',['app','template']);
            $table->string('title');
            $table->string('slug');
            $table->string('price')->default(0);
            $table->string('new_price')->default(0);
            $table->string('image1');
            $table->string('image2');
            $table->string('image3');
            $table->string('image4');
            $table->string('file')->nullable();
            $table->text('attrbutes');
            $table->text('description');
            $table->integer('status')->default(0);
            $table->integer('CountDownload')->default(0);
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
        Schema::dropIfExists('products');
    }
};
