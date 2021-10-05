<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role_id = DB::table('roles')->where('name', 'admin')->value('id');
        $admin_id = DB::table('admins')->where('email', 'admin')->value('id');
        DB::table('admin_roles')->insert([
           "role_id" => $role_id,
           "admin_id" => $admin_id,
           "created_at" => Carbon::now()
        ]);
    }
}
