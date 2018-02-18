<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('session_id')->comment('学期ID');
            $table->unsignedSmallInteger('teacher_id')->comment('教师ID');
            $table->unsignedtinyInteger('grade')->comment('年级 1、2、3');
            $table->unsignedtinyInteger('teach_id')->comment('科目ID');
            $table->unsignedtinyInteger('leader')->comment('学科组长 0->否 1->是')->default(0);
            $table->string('remark', 50)->nullable()->comment('备注');
            $table->softDeletes();
            $table->timestamps();
            $table->comment = '分学期教研组长信息表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
