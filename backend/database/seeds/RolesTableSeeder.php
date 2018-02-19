<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->insert([
            'name' => 'admin',
            'explain' => '管理员',
            'remark' => '管理所有的一切，无法修改其功能权限',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()

        ]);
        DB::table('roles')->insert([
            'name' => 'user',
            'explain' => '用户',
            'remark' => '普通用户，权限可以自定义',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
