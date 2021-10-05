<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->string('email')->comment('登陆名');
            $table->string('phone')->comment('手机号码')->nullable();
            $table->string('avatar')->nullable()->comment('头像');
            $table->string('password')->nullable()->comment('密码');
            $table->tinyInteger('status')->default(1)->comment('用户状态');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
            $table->unique('email');
            $table->unique('phone');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
