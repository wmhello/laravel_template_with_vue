<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => 'wmhello',
            'email' => '871228582@qq.com',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'avatar' => 'uploads/201711251441th5a19812148058.jpg',
            'remember_token' => str_random(10),
        ]);

        DB::table('users')->insert([
            'name' => 'dongdong',
            'email' => '786270744@qq.com',
            'password' => bcrypt('123456'),
            'role' => 'editor',
            'avatar' => 'uploads/201711251509th5a19879c71868.jpg',
            'remember_token' => str_random(10),
        ]);
    }
}
