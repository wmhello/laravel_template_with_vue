<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_number')->comment('产品编号');
            $table->string('product_name')->nullable()->comment('产品名称');
            $table->string('product_img')->nullable()->comment('产品图象');
            $table->integer('order_id')->default(1)->comment('订单id');
            $table->unsignedSmallInteger('product_amount')->default(1)->comment('产品数量');
            $table->unsignedSmallInteger('remain_amount')->default(1)->comment('剩余数量');
            $table->string('product_remark')->nullable()->comment('产品备注');
            $table->softDeletes();  // 软删除
            $table->timestamps();   // 维护记录的建立和更新时间
            $table->index('product_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
