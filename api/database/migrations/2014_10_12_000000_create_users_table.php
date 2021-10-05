<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nickname')->comment('昵称')->nullable();
            $table->string('email')->comment('登陆名')->nullable();
            $table->string('password')->nullable()->comment('密码');
            $table->string('phone', 11)->comment('手机号码')->nullable();;
            $table->string('open_id')->comment('微信端用户ID')->nullable();;
            $table->string('union_id')->comment('微信端用户联合ID')->nullable();;
            $table->string('avatar')->nullable()->comment('用户头像');
            $table->boolean('status')->default(1)->comment('用户状态');
            $table->tinyInteger('gender')->default(0)->comment('用户');
            $table->string('country')->nullable()->comment('用户国家');
            $table->string('province')->nullable()->comment('用户所在省');
            $table->string('city')->nullable()->comment('用户所在市');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
            $table->unique('email');
            $table->unique('phone');
            $table->unique('open_id');
            $table->unique('union_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
