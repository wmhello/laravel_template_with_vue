<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yz_teacher', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('姓名');
            $table->char('sex', 1)->comment('性别');
            $table->unsignedTinyInteger('nation_id')->comment('民族ID');
            $table->date('birthday')->comment('生日');
            $table->date('work_time')->comment('工作日期');
            $table->string('political')->comment('政治面貌')->default('群众');
            $table->date('join_date')->comment('入党、团时间')->nullable();
            $table->string('card_id')->comment('身份证');
            $table->char('phone', 13)->comment('联系号码1');
            $table->char('phone2', 13)->comment('联系号码2')->nullable();
            $table->unsignedTinyInteger('type')->comment('类型(1->教师 0->职工)')->default(1);
            $table->string('open_id')->comment('微信绑定OpenId')->nullable();
            $table->unsignedTinyInteger('state')->comment('人员状态(1->正常 2->调出 3->退出 4->停薪留职)')->default(1);
            $table->unsignedTinyInteger('teaching_id')->comment('教学科目Id')->nullable;
            $table->timestamps();
            $table->unique('card_id');
            $table->unique('phone');
            $table->unique('phone2');
            // 要设置外键的话  必须先保证相应的表格先存在
            // $table->foreign('teaching_id')->reference('id')->on('yz_teaching');
            // $table->foreign('nation_id')->reference('id')->on('yz_nation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yz_teacher');
    }
}
