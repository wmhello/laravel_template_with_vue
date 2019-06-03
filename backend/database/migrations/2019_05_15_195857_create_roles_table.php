<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('角色名称，对应用户表');  // user
            $table->string('explain')->comment('角色说明，中文');  // 普通用户
            $table->string('permissions')->nullable()->comment('角色对应的权限id，对应权限表');
            $table->string('remark')->nullable()->comment('角色备注');
            $table->softDeletes();
            $table->timestamps();
        });
    }

        //id
        //name  (名称，用户表关联使用，不能相同)
        //explain(角色说明，必须填写)
        //permissions(具体的权限， 1,2,3， 对应permissions表)
        //remark （备注）

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
