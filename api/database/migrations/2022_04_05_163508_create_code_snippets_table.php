<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodeSnippetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_snippets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->comment('代码方案名称');
            $table->string('desc')->nullable()->comment('代码方案说明');
            $table->text('front_api')->nullable()->comment('前端api代码片段');
            $table->text('front_model')->nullable()->comment('前端模型代码片段');
            $table->text('front_page')->nullable()->comment('前端页面代码片段');
            $table->text('back_routes')->nullable()->comment('后端路由代码片段');
            $table->text('back_model')->nullable()->comment('后端模型代码片段');
            $table->text('back_resource')->nullable()->comment('后端资源代码片段');
            $table->text('back_api')->nullable()->comment('后端API接口代码片段');
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
        Schema::dropIfExists('code_snippets');
    }
}
