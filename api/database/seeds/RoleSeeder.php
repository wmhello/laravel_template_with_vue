<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $result = [
            [
                "name" => "admin",
                "desc" => "超级管理员",
                "created_at" => Carbon::now()
            ],
            [
                "name" => "user",
                "desc" => "普通用户",
                "created_at" => Carbon::now()
            ]
        ];
        \App\Models\Role::insert($result);
    }
}
