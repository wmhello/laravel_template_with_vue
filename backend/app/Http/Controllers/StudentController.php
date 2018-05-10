<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Import\StudentImport;
use App\Http\Requests\StoreStudentPost;
use App\Http\Requests\UpdateStudentPatch;
use App\Http\Resources\StudentCollection;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    public function index(Request $request)
    {
        //
        $pageSize = $request->input('pageSize', 10);
        $data = Student::paginate($pageSize);
        return new StudentCollection($data);
    }



    public function store(StoreStudentPost $request)
    {
        //
        $data = $request->only(['student_name', 'student_sex', 'student_phone', 'student_status', 'student_remark']);

        if (Student::create($data)) {
            return $this->success();
        } else {
            return $this->error();
        }
    }


    public function show(Student $student)
    {
        //
        return new \App\Http\Resources\Student($student);
    }


    public function update(UpdateStudentPatch $request, Student $student)
    {
        //
        $data = $request->only(['student_name', 'student_sex', 'student_phone', 'student_status', 'student_remark']);

        if (Student::where('student_id', $student['student_id'])->update($data)) {
           return $this->success();
        } else {
            return $this->errorWithInfo('更新学生信息失败');
        }
    }


    public function destroy(Student $student)
    {
        //
        if ($student->delete()) {
            return $this->success();
        } else {
            return $this->errorWithInfo('删除记录失败，可能记录不存在');
        }
    }

    public function upload(StudentImport $import)
    {
        $bool = $import->handleImport($import);
        if ($bool) {
            return $this->success();
        } else {
            return $this->error();
        }
    }


    protected  function queryData($pageSize = null, $page = 1){
        // 查询条件  根据姓名或者电话号码进行查询
        $offset = $pageSize * ($page - 1) == 0? 0: $pageSize * ($page - 1);
        $model = $this->getModel();
        $lists = $model::select('student_name', 'student_sex', 'student_phone', 'student_status', 'student_remark')
            ->when($pageSize,function($query) use($offset, $pageSize) {
                return $query->offset($offset)->limit($pageSize);
            })
            ->get();
        return $lists;
    }

    /**
     * 根据传入的数据生成内容
     * @param $data
     * @return array
     */
    protected function generatorData($data): array
    {
        $items = [];
        foreach ($data as $item) {
            $arr = [];
            $arr['student_name'] = $item['student_name'];
            $arr['student_sex'] = $item['student_sex'];
            $arr['student_phone'] = $item['student_phone'];
            $arr['student_status'] = $item['student_status'] ==0?'禁用':'启用';
            $arr['student_remark'] = $item['student_remark'];
            array_push($items, $arr);
        }
        array_unshift($items, ['姓名', '性别', '电话号码', '状态', '备注']); // 标题
        return $items;
    }


    protected function getModel() {
        return 'App\Models\Student';
    }


    protected function getExportFile() {
        return '学生管理';
    }
}
