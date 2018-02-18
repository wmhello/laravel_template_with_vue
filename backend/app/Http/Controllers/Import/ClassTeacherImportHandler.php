<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21 0021
 * Time: 18:05
 */

namespace App\Http\Controllers\Import;

use App;
use App\Http\Controllers\Import\LeaderImport;
use App\Rules\Telphone;
use App\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ClassTeacherImportHandler implements \Maatwebsite\Excel\Files\ImportHandler
{
    use App\Http\Controllers\Result;
    use App\Http\Controllers\Tools;
    public function handle($import)
    {
        $data = $import->first()->toArray();
        $lists = [];
        $session_id = $this->getCurrentSessionId();
        foreach($data as $val) {
            // 获取每条记录  为验证做准备
            $item = [
                'name' => $val['name'],
                'grade' => (int)$val['grade'],
                'class_id' => (int)$val['class_id'],
                'remark' => $val['remark'],
                'phone' => $val['phone'],
                'session_id' => $session_id,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now()
            ];
            // 根据规则进行验证，保证输入的内容符合要求  电话号码如果填写要验证  类型只能为1和2
            $rules = [
                'name' => 'required|exists:yz_teacher,name',
                'phone' => ['nullable', new Telphone],
                'grade' => 'required|in:1,2,3',
                'class_id' => 'required|integer'
            ];
            $validator = Validator::make($item, $rules);

            if (!$validator->fails()) { // 成功
                // 验证成功后要 进行姓名处理  根据姓名  从Teacher中取出id
                if (!isset($item['phone'])) {
                    // 名字处理  ，看看是否有同名同姓的 ，没有手机号码而且又姓名相同，则无法处理  未知是那个用户
                    $count = Teacher::where('name', $item['name'])->count();
                    if ($count>1) {
                        continue;
                    }
                    // 姓名唯一，取出该教师的ID号，用于在领导表中保存
                    $teacher_id = Teacher::where('name', $item['name'])->value('id');
                    if ($teacher_id) {
                        $item['teacher_id'] = $teacher_id;
                        unset($item['name']);
                        unset($item['phone']);
                        array_push($lists, $item);
                    } else {  // 没有找到指定姓名的教师，则跳过处理该记录
                        continue;
                    }
                } else {
                    // 有手机号，则用手机号和用户姓名同时来取教师ID
                    $teacher_id = Teacher::where('name', $item['name'])->where('phone', $item['phone'])->value('id');
                    if ($teacher_id) {
                        $item['teacher_id'] = $teacher_id;
                        unset($item['name']);
                        unset($item['phone']);
                        array_push($lists, $item);
                    } else {  // 教师表里面没有该教师信息，则处理下一条记录
                        continue;
                    }
                }
            } else {
                  $errors = $validator->errors($validator);
                  return $this->errorWithCodeAndInfo(422,$errors);
            }
        }
        // 删除原来存在的该学期的数据
        if (App\ClassTeacher::where('session_id',$session_id)->count()){
            App\ClassTeacher::where('session_id',$session_id)->delete();
        }
        // 插入数据到领导表
//        array_walk($lists, function($v){
//            App\App\ClassTeacher::Create($v);
//        });
        return App\ClassTeacher::insert($lists);
    }

}