<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaycomTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paycom_orders', function (Blueprint $table) {
            $table->id();
            $table->string('product_ids');
            $table->decimal('amount', 16, 2);
            $table->tinyInteger('state');
            $table->integer('user_id');
            $table->string('phone', 20);
            $table->timestamps();
        });

        Schema::create('paycom_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('paycom_transaction_id', 50);
            $table->string('paycom_time', 20);
            $table->dateTime('paycom_time_datetime');
            $table->dateTime('create_time');
            $table->dateTime('perform_time')->nullable()->default(null);
            $table->dateTime('cancel_time')->nullable()->default(null);
            $table->integer('amount');
            $table->tinyInteger('state');
            $table->tinyInteger('reason')->nullable()->default(null);
            $table->string('receivers', 500)->nullable()->comment('JSON array of receivers');
            $table->integer('order_id');
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
        Schema::dropIfExists('paycom_transactions');
        Schema::dropIfExists('paycom_orders');
    }
}
