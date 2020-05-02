<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->text('product_code');
            $table->text('name');
            $table->integer('category_id');
            $table->integer('unit_id');
            $table->text('image')->nullable();
            $table->float('unit_price', 10, 2);
            $table->float('qty', 10, 2);
            $table->integer('discount')->nullable();
            $table->integer('hits')->nullable();
            $table->longText('description');
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
}
