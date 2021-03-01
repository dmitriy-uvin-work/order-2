<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('iid')->unique()->index();
            $table->string('name', 255)->nullable();
            $table->string('short_description', 500)->nullable();
            $table->string('note', 255)->nullable();
            $table->string('color', 10)->default('black')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('image', 100)->nullable();
            $table->text('body')->nullable();
            $table->tinyInteger('active')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->integer('priority')->nullable();
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
        Schema::dropIfExists('stocks');
    }
}
