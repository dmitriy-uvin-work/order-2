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
            $table->unsignedBigInteger('iid')->unique()->index();
            $table->string('name',255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->float('price')->nullable();
            $table->string('image', 100)->nullable();
            $table->text('gallery')->nullable();
            $table->string('description', 1000)->nullable();
            $table->string('information', 1000)->nullable();
            $table->string('volume')->nullable();
            $table->integer('quantity')->nullable();
            $table->bigInteger('brand_id')->index()->nullable();
            $table->bigInteger('group_id')->index()->nullable();
            $table->tinyInteger('status')->index()->default(0);
            $table->tinyInteger('deleted')->default(0);
            $table->timestamp('last_update')->nullable();
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
