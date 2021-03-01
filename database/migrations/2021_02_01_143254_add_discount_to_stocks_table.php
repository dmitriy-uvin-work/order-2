<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountToStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->float('discount', 8, 2)->nullable();
            $table->tinyInteger('site_active')->default(1);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('stock_id')->index()->nullable();
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->bigInteger('stock_id')->index()->nullable();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->bigInteger('stock_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('stock_id');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('stock_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stock_id');
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('discount');
        });
    }
}
