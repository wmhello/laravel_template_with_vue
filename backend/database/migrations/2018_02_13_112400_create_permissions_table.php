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
            $table->string('name')->comment('功能名称');
            $table->unsignedInteger('pid')->comment('父节点');
            $table->unsignedTinyInteger('type')->comment('节点类型(1->分组 2->节点)');
            $table->string('method')->comment('方法');
            $table->string('route_name')->comment('路由名称');
            $table->string('route_match')->nullable()->comment('路由模式');
            $table->string('remark')->nullable()->comment('功能备注');
            $table->softDeletes();
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
        Schema::dropIfExists('permissions');
    }
}
