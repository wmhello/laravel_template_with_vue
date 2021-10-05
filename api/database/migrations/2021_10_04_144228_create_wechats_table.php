<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWechatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_id')->nullable()->comment('微信应用APP_ID');
            $table->string('app_secret')->nullable()->comment('微信应用APP_Secret');
            $table->string('type')->comment('微信应用类型');
            $table->boolean('status')->default(1)->comment('微信应用是否启用');
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
        Schema::dropIfExists('wechats');
    }
}
