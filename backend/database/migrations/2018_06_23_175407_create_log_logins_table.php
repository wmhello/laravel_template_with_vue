<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_logins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户ID');
            $table->string('user_name')->comment('用户名称');
            $table->char('ip',20)->comment('用户IP地址');
            $table->char('type',10)->comment('事件类型');
            $table->string('desc')->comment('事件描述');
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
        Schema::dropIfExists('log_logins');
    }
}
