<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodeConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_configs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('front_dir')->nullable()->comment('前端目录配置');
            $table->string('front_api')->nullable()->comment('前端api目录配置');
            $table->string('front_model')->nullable()->comment('前端model目录配置');
            $table->string('front_view')->nullable()->comment('前端view目录配置');
            $table->string('back_dir')->nullable()->comment('后端目录配置');
            $table->string('back_api')->nullable()->comment('后端控制器目录配置');
            $table->string('back_model')->nullable()->comment('后端models目录配置');
            $table->string('back_routes')->nullable()->comment('后端routes目录配置');
            $table->string('back_resource')->nullable()->comment('后端resource目录配置');
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
        Schema::dropIfExists('code_configs');
    }
}
