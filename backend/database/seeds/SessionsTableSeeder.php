<?php

use Illuminate\Database\Seeder;

class SessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sessions')->insert([
            'year' => 2017,
            'team' => 1,
            'one' => 22,
            'two' => 22,
            'three' => 19,
            'remark' => '2017-2018学年上学期'
        ]);

        DB::table('sessions')->insert([
            'year' => 2017,
            'team' => 2,
            'one' => 22,
            'two' => 22,
            'three' => 19,
            'remark' => '2017-2018学年上学期'
        ]);
    }
}
