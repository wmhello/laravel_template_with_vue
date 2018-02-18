<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('session_id')->comment('学期ID');
            $table->unsignedSmallInteger('teacher_id')->comment('教师ID');
            $table->unsignedTinyInteger('leader_type')->comment('职务类型 1->中层  2->校级')->default(1);
            $table->string('job', 20)->comment('工作岗位说明')->nullable();
            $table->string('remark',50)->nullable()->comment('备注');
            $table->softDeletes();
            $table->timestamps();
            $table->comment = '分学期领导信息表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leaders');
    }
}
