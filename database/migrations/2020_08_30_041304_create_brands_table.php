<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iid')->unique()->index();
            $table->string('name', 100)->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('image',100)->nullable();
            $table->bigInteger('country_id')->index()->nullable();
            $table->string('description', 1000)->nullable();
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
        Schema::dropIfExists('brands');
    }
}
