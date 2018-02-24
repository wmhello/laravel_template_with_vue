<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $id = DB::table('permissions') ->insertGetId([
            'name' => '学期管理',
            'pid' => 0,
            'type' => 1,
            'method' => '',
            'route_name' => '',
            'route_match' => '',
            'remark' => '管理学期信息',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('permissions') ->insert([
        'name' => '获取学期列表',
        'pid' => $id,
        'type' => 2,
        'method' => 'GET',
        'route_name' => 'session.index',
        'route_match' => '/^\/api\/session$/',
        'remark' => '获取学期列表信息',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
        ]);

        DB::table('permissions') ->insert([
            'name' => '获取指定的学期信息',
            'pid' => $id,
            'type' => 2,
            'method' => 'GET',
            'route_name' => 'session.show',
            'route_match' => '/^\/api\/session\/\d+$/',
            'remark' => '获取指定的学期信息',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('permissions') ->insert([
            'name' => '创建新的学期信息',
            'pid' => $id,
            'type' => 2,
            'method' => 'POST',
            'route_name' => 'session.store',
            'route_match' => '/^\/api\/session$/',
            'remark' => '创建新的学期信息',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('permissions') ->insert([
            'name' => '更新指定的学期信息',
            'pid' => $id,
            'type' => 2,
            'method' => 'PATCH',
            'route_name' => 'session.update',
            'route_match' => '/^\/api\/session\/\d+$/',
            'remark' => '更新指定的学期信息',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('permissions') ->insert([
            'name' => '删除指定的学期信息',
            'pid' => $id,
            'type' => 2,
            'method' => 'DELETE',
            'route_name' => 'session.destroy',
            'route_match' => '/^\/api\/session\/\d+$/',
            'remark' => '删除指定的学期信息',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $id2 = DB::table('permissions') ->insertGetId([
            'name' => '行政管理',
            'pid' => 0,
            'type' => 1,
            'method' => '',
            'route_name' => '',
            'route_match' => '',
            'remark' => '管理行政信息',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

    }
}
