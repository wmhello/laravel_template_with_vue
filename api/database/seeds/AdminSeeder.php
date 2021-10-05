<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('admins')->insert([
            "nickname" => 'admin',
            "email" => 'admin',
            "password" => bcrypt("123456"),
            "status" => 1,
            "created_at" => Carbon::now()
        ]);
    }
}
