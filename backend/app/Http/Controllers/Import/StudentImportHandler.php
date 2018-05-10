<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21 0021
 * Time: 18:05
 */

namespace App\Http\Controllers\Import;

use App\Models\Student;
use Carbon\Carbon;

class StudentImportHandler implements \Maatwebsite\Excel\Files\ImportHandler
{
    public function handle($import)
    {
        // get the results
        // 获取第一个工作表电子表格的数据
        $result = $import->first()->toArray();
        $lists = [];
        foreach ($result as $v){
            $data = [
                'student_name' => $v['姓名'],
                'student_sex' =>$v['性别'],
                'student_phone' =>(string)$v['手机号码'],
                'student_status' =>$v['状态'] == '启用'? 1:0,
                'student_remark' =>$v['备注'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            if (Student::where('student_phone', $data['student_phone'])->first()){  // 手机号码存在重复记录
                continue;
            } else { // 记录先暂时保存到数组，稍后一次新建
                array_push($lists,$data);
            }
        }
        return Student::insert($lists);
    }

}