<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21 0021
 * Time: 18:05
 */

namespace App\Http\Controllers\Import;

use App\User;
use Carbon\Carbon;

class UserImportHandler implements \Maatwebsite\Excel\Files\ImportHandler
{
    public function handle($import)
    {
        // get the results
        // 获取第一个工作表电子表格的数据
        $result = $import->first()->toArray();
        $lists = [];
        foreach ($result as $v){
            $data = [
                'name' => $v['name'],
                'email' =>$v['email'],
                'role' =>'user',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'password' => bcrypt('123456')
            ];
            if (User::where('email', $data['email'])->first()){  // 存在重复记录
                continue;
            } else { // 记录先暂时保存到数组，稍后一次新建
                array_push($lists,$data);
            }
        }
        return User::insert($lists);
    }

}