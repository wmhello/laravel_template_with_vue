<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('table_name')->nullable()->comment("表名称");
            $table->string('table_comment')->nullable()->comment('表注释');
            $table->string('engine')->nullable()->comment('数据表引擎');
            $table->string('table_collation')->nullable()->comment('字符编码集');
            $table->dateTime('create_time')->nullable()->comment('创建日期');
            $table->text('table_config')->nullable()->comment('表的基础配置');
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
        Schema::dropIfExists('tables');
    }
}
