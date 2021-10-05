<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->comment('文章标题');
            $table->string('img')->comment('文章封面');
            $table->string('summary')->nullable()->comment('文章描述');
            $table->text('content')->comment('文章内容');
            $table->boolean('status')->default(true)->comment('文章启用状态');
            $table->unsignedInteger('order')->default(1)->comment('文章顺序');
            $table->unsignedBigInteger('article_category_id')->comment('文章种类标识');
            $table->foreign('article_category_id')->references('id')->on('article_categories');
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
        Schema::dropIfExists('articles');
    }
}
