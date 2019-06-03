<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number')->unique()->comment('订单编号');  // varchar(255)
            $table->string('merchant_number')->comment('客户编号');
            $table->string('merchant_name')->nullable()->comment('客户名称');
            $table->tinyInteger('order_status')->default(1)->comment('订单状态'); //tinyint 1->未发货 2->已发未完 3->已发完
            $table->timestamp('order_time')->comment('订单日期');
            $table->string('order_remark')->nullable()->comment('备注');
            $table->softDeletes();  // 软删除
            $table->timestamps();   // 维护记录的建立和更新时间
            $table->index('order_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
