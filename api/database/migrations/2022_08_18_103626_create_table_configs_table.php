<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_configs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('table_name')->nullable()->comment("表名称");
            $table->string('column_name')->nullable()->comment("字段名称");
            $table->string('data_type')->nullable()->comment("字段类型");
            $table->string('column_comment')->nullable()->comment("字段描述");
            $table->boolean('is_required')->nullable()->comment("是否必填项目")->default(0);
            $table->boolean('is_list')->nullable()->comment("是否列表项目")->default(0);
            $table->boolean('is_form')->nullable()->comment("是否表单项目")->default(0);
            $table->string('form_type')->nullable()->comment("表单类型");
            $table->string('query_type')->nullable()->comment("查询方式");
            $table->string('assoc_table')->nullable()->comment("关联表");
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
        Schema::dropIfExists('table_configs');
    }
}
