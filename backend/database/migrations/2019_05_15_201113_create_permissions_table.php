<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('权限名称');
            $table->unsignedSmallInteger('pid')->comment('权限的从属关系(0->顶级节点)');
            $table->unsignedTinyInteger('type')->comment('权限类型（1->组 2->功能节点）');
            $table->string('route_name')->nullable()->comment('路由名称');
            $table->string('remark')->nullable()->comment('权限备注');
            $table->softDeletes();
            $table->timestamps();
        });
    }


    //表结构
    //id
    //name（权限名称 中文）
    //explain（权限说明）
    //pid （权限的从属关系）
    //type (权限类型 0->组 1->节点)
    //route(路由功能名称)
    //remark

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
