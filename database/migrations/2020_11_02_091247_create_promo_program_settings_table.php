<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoProgramSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_program_settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('iid')->unique()->index();
            $table->integer('program_id');
            $table->string('key',255);
            $table->string('value',1000)->nullable();
            $table->float('discount')->nullable();
            $table->integer('interaction_method')->nullable();
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
        Schema::dropIfExists('promo_program_settings');
    }
}
