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
            $table->string('name');
            $table->string('feature_image_name')->nullable();
            $table->string('feature_image_path')->nullable();
            $table->string('slug')->nullable();
            $table->integer('category_id');
            $table->float('price', 11, 2);
            $table->integer('quantity');
            $table->text('details');
            $table->integer('user_id');
            $table->timestamps();
            $table->integer('sell')->default(0);
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
