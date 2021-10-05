<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(AdminRoleSeeder::class);
        $this->call(WechatSeeder::class); // 微信配置数据表
        $this->call(ViewSeeder::class); // 建立视图
    }
}
