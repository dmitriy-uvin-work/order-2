<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->float('price');
            $table->float('net_price')->nullable();
            $table->integer('delivery_price')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0-Ожидание оплаты; 1-Обработка заказа; 2-Завершен; 3-Ошибка при оплате; 5-Отменен');
            $table->string('phone', 20)->nullable();
            $table->tinyInteger('payment_status')->default(0)->comment('0-Ожидание оплаты; 2-Оплачено; 3-Ошибка при оплате; 5-Отменен');
            $table->tinyInteger('payment_type')->nullable()->comment('1-Payme; 2-Click; 3-Наличными');
            $table->tinyInteger('delivery_type')->nullable()->comment('1-Доставка; 2-Самовывоз');
            $table->integer('delivery_region')->nullable();
            $table->integer('delivery_district')->nullable();
            $table->string('delivery_address', 500)->nullable();
            $table->string('comment', 1000)->nullable();
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
        Schema::dropIfExists('orders');
    }
}
