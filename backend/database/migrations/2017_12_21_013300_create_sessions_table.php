<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('year')->comment('学年'); // 2016代表2016-2017  2017代表2017-2018
            $table->unsignedTinyInteger('team')->default('1')->comment('学期 1 上学期 2 下学期');  // 1->上学期 2->下学期
            $table->string('remark', 50)->nullable()->comment('备注');
            $table->softDeletes();
            $table->timestamps();
            $table->comment ='学期表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}
