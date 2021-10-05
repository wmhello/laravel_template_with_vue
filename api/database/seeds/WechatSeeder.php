<?php

use Illuminate\Database\Seeder;

class WechatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
          [
              'app_id' => '',
              'app_secret' => '',
              'type' => 'mp', // 小程序
              'created_at' => now()
          ],
          [
              'app_id' => '',
              'app_secret' => '',
              'type' => 'office', // 小程序
              'created_at' => now()
          ],
        ];

        \App\Models\Wechat::insert($data);
    }
}
