<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_id')->comment('管理员标识');
            $table->string('name')->comment('登陆者--使用者');
            $table->string('address')->comment('地址--IP');
            $table->enum('event', ['login', 'system'])->comment('操作类型--登陆操作、系统操作');
            $table->string('desc')->comment('操作描述');
            $table->string('result')->comment('操作结果');
            $table->string('content')->comment('操作内容')->nullable();
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
        Schema::dropIfExists('logs');
    }
}
