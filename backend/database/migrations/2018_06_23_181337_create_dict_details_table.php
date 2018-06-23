<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDictDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dict_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dict_id')->comment('字典分类Id');
            $table->tinyInteger('level')->comment('分类下详细种类');
            $table->string('desc') ->comment('种类描述');
            $table->string('remark')->comment(' 备注')->nullable();
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
        Schema::dropIfExists('dict_details');
    }
}
