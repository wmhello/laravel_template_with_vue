<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('session_id')->comment('学期ID');
            $table->unsignedSmallInteger('teacher_id')->comment('教师ID');
            $table->unsignedtinyInteger('grade')->comment('年级 1、2、3');
            $table->unsignedtinyInteger('class_id')->comment('班级');
            $table->string('remark', 50)->nullable()->comment('备注');
            $table->softDeletes();
            $table->timestamps();
            $table->comment = '分学期班主任信息表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_teachers');
    }
}
