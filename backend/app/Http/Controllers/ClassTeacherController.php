<?php

namespace App\Http\Controllers;

use App\ClassTeacher;
use App\Http\Controllers\Import\ClassTeacherImport;
use App\Http\Requests\ClassTeacherRequest;
use App\Http\Requests\ClassTeacherUploadRequest;
use App\Http\Resources\ClassTeacherCollection;
use App\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ClassTeacherController extends Controller
{
    use Result, Tools;

    /**
     * @api {get} /api/classTeacher 获取班主任列表
     * @apiGroup classTeacher
     *
     * @apiParam {number} [session_id] 学期ID 默认为当前学期
     * @apiParam {number} [teacher_id] 教师ID 默认为空
     * @apiSuccessExample 获取班主任列表，分页显示，每页15条记录,
     * HTTP/1.1 200 OK
     * {
     * "data": [
     * {
     * "id": 1,
     * "session_id": 3,
     * "teacher_id": 140,
     * "grade": 1,
     * "class": 1,
     * "remark": null
     * }
     * ],
     * "status": "success",
     * "status_code": 200,
     * "links": {
     * "first": "http://manger.test/api/classTeacher?page=1",
     * "last": "http://manger.test/api/classTeacher?page=1",
     * "prev": null,
     * "next": null
     * },
     * "meta": {
     * "current_page": 1,
     * "from": 1,
     * "last_page": 1,
     * "path": "http://manger.test/api/classTeacher",
     * "per_page": 15,
     * "to": 13,
     * "total": 13
     * }
     * }
     */
    public function index(Request $request)
    {

        $pageSize = $request->input('pageSize');
        $pageSize = isset($pageSize) && $pageSize ?$pageSize:10;
        $lists = ClassTeacher::SessionId()->TeacherId()->Grade()->paginate($pageSize);
        return new ClassTeacherCollection($lists);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * @api {post} /api/classTeacher 创建新的班主任信息
     * @apiGroup classTeacher
     *
     * @apiParam {number} session_id 学期ID
     * @apiParam {number} teacher_id 教师ID
     * @apiParam {number} class 班级
     * @apiParam {number=1,2,3} grade 年级
     * @apiParam {string} [remark] 备注 可选
     * @apiParamExample {object} 请求事例 创建新的班主任信息:
     * {
     * session_id: 3,
     * teacher_id: 168,
     * class: 10,
     * grade: 1,
     * remark: '高一10班'
     * }
     *
     * @apiSuccessExample {json} 操作成功:
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200
     * }
     * @apiErrorExample {json} 数据验证出错:
     * HTTP/1.1 422 Not Found
     * {
     * "status": 422,
     * }
     * @apiErrorExample {json} 指定的班级不存在:
     * HTTP/1.1 416 Satisfiable
     * {
     * "status": 'error',
     * "status_code": 416,
     * "message": '数据校验出错，指定的班级不存在'
     * }
     * @apiErrorExample {json} 操作失败:
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404
     * }
     */
    public function store(ClassTeacherRequest $request)
    {
        //
        $data = $request->only(['teacher_id', 'grade', 'class_id', 'remark']);
        $data['session_id'] = $this->getCurrentSessionId();
        if (! $this->checkClass($data)) {
          return $this->errorWithCodeAndInfo(422, '数据校验出错，指定的班级不存在');
        };
        if (ClassTeacher::create($data)) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * @api {get} /api/classTeacher/:id 获取指定的班主任信息
     * @apiGroup classTeacher
     *
     * @apiParam {number} id 班主任标识ID
     * @apiSuccessExample 获取班主任列表，分页显示，每页15条记录,
     * HTTP/1.1 200 OK
     * {
     * "data": [
     * {
     * "id": 1,
     * "session_id": 3,
     * "teacher_id": 140,
     * "grade": 1,
     * "class": 1,
     * "remark": null
     * }
     * ],
     * "status": "success",
     * "status_code": 200
     * }
     */
    public function show(ClassTeacher $classTeacher)
    {
        //
        return new \App\Http\Resources\ClassTeacher($classTeacher);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ClassTeacher  $classTeacher
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassTeacher $classTeacher)
    {
        //
    }

    /**
     * @api {patch} /api/classTeacher/:id 更新指定的班主任信息
     * @apiGroup classTeacher
     *
     * @apiParam {number} id 班主任标识
     * @apiParam {number} session_id 学期ID
     * @apiParam {number} teacher_id 教师ID
     * @apiParam {number} class 班级
     * @apiParam {number=1,2,3} grade 年级
     * @apiParam {string} [remark] 备注 可选
     * @apiParamExample {object} 请求事例 创建新的班主任信息
     * {
     * id: 15,
     * session_id: 3,
     * teacher_id: 168,
     * class: 10,
     * grade: 1,
     * remark: '高一10班'
     * }
     *
     * @apiSuccessExample {json} 操作成功
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200
     * }
     * @apiErrorExample {json} 数据验证出错
     * HTTP/1.1 422 Not Found
     * {
     * "status": 422,
     * }
     * @apiErrorExample {json} 指定的班级不存在
     * HTTP/1.1 416 Satisfiable
     * {
     * "status": 'error',
     * "status_code": 416,
     * "message": '数据校验出错，指定的班级不存在'
     * }
     * @apiErrorExample {json} 操作失败
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404
     * }
     */

    public function update(ClassTeacherRequest $request, ClassTeacher $classTeacher)
    {
        //
        $data = $request->only(['teacher_id', 'grade', 'class_id', 'remark']);
        $data['session_id'] = $this->getCurrentSessionId();
        if (! $this->checkClass($data)) {
            return $this->errorWithCodeAndInfo(416, '数据校验出错，指定的班级不存在');
        };
        $classTeacher->session_id = $data['session_id'];
        $classTeacher->teacher_id = $data['teacher_id'];
        $classTeacher->grade = $data['grade'];
        $classTeacher->class_id = $data['class_id'];
        $classTeacher->remark = $data['remark'];
        if ($classTeacher->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * @api {delete} /api/classTeacher/:id 删除指定的班主任信息
     * @apiGroup classTeacher
     *
     * @apiParam {number} id 班主任标识

     * @apiSuccessExample {json} 操作成功
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200
     * }
     * @apiErrorExample {json} 操作失败 指定的信息不存在
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404
     * }
     */

    public function destroy(ClassTeacher $classTeacher)
    {
        //
        if ($classTeacher->delete()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function deleteAll(Request $request)
    {
        $data = $this->deleteByIds($request);
        if ($data) {
            if (ClassTeacher::destroy($data['ids'])) {
                return $this->success();
            } else {
                return $this->error();
            }
        }
    }

    /**
     * @api {post} /api/classTeacher/upload 导入班主任信息
     * @apiGroup classTeacher
     *
     * @apiParam {number} session_id 学期ID
     * @apiParam {string} file 要导入的文件

     * @apiHeaderExample {json} http头部例子
     *     {
     *       "content-type": "multipart/form-data"
     *     }
     *
     * @apiParamExample {object} 请求事例 导入指定学期的班主任数据
     * {
     * session_id: 3,
     * file: 'd:/3.xls'
     * }
     *
     * @apiSuccessExample {json} 操作成功
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200
     * }
     * @apiErrorExample {json} 操作失败
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404
     * }
     */

    public function upload( ClassTeacherImport $import, ClassTeacherUploadRequest $request)
    {
        $bool = $import->handleImport($import);
        if ($bool) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * 验证是否存在指定的班级 如果该学期的指定年级 没有指定的班级，则显示数据校验出错
     * @param $data
     * @return bool|\Illuminate\Http\JsonResponse
     */

    protected function checkClass($data)
    {
        list($session_id, $class, $grade) = [(int)$data['session_id'], (int)$data['class_id'], (int)$data['grade']];
        $session = Session::where('id', $session_id)->first()->toArray();
        $arrGrade = ['zero', 'one', 'two', 'three'];
        $maxClass = $session[$arrGrade[$grade]];
        if ($class > $maxClass || $class <=0) {
            return false;
        } else {
            return true;
        }
    }

    public function exportAll(Request $request) {
        $sessionId =$request->input('session_id');
        $sessionId = isset($sessionId)?$sessionId:$this->getCurrentSessionId();
        $rec = ClassTeacher::where('session_id', $sessionId)->count(); // 获得总记录数,因为是所有的数据
        $this->generator($rec, 1);
    }

    public function export(Request $request)
    {
        $pageSize = (int)$request->input('pageSize');
        $pageSize = isset($pageSize) && $pageSize? $pageSize: 10;
        $page = (int)$request->input('page');
        $page = isset($page) && $page ? $page: 1;
        $this->generator($pageSize, $page);
    }

    public function generator($pageSize, $page)
    {

        $sessionId = (int)request()->input('session_id');
        $teacherId = (int)request()->input('teacher_id');
        $grade = (int)request()->input('grade');

        $sessionId = (isset($sessionId)&&$sessionId)?$sessionId: $this->getCurrentSessionId();
        $teacherId = (isset($teacherId)&&$teacherId)?$teacherId: null;
        $grade = (isset($grade)&&$grade)?$grade: [1,2,3];
        if (is_numeric($grade)) {
            $arr = [];
            array_push($arr,$grade);
            $grade = $arr;
        }
        $lists = $this->queryData($pageSize, $page,$sessionId, $grade,$teacherId);
        $data = $lists->toArray();  // 分页内容
        $items = $this->generatorData($data);
        $this->generatorXls($items);
    }

    protected  function queryData($pageSize = 10, $page = 1, $sessionId, $grade,$teacherId){
        // 查询条件  根据姓名或者电话号码进行查询
        $offset = $pageSize * ($page - 1) == 0? 0: $pageSize * ($page - 1);
        $lists = DB::table('class_teachers')->join('yz_teacher', 'class_teachers.teacher_id','=', 'yz_teacher.id')
            ->join('sessions', 'class_teachers.session_id', '=', 'sessions.id' )
            ->select(['yz_teacher.name', 'sessions.year', 'sessions.team','class_teachers.grade', 'class_teachers.class_id', 'class_teachers.remark'])
            ->where('session_id', $sessionId)
            ->whereIn('grade', $grade)
            ->when($teacherId,function ($query) use ($teacherId) {
                return $query->where('teacher_id', $teacherId);
            })
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
        // $data = $data['data'];  // 数据库中的数据
        foreach ($data as $item) {
            $arr = [];
            $arr['name'] = $item->name;
            $nextYear = $item->year + 1;
            $arr['year'] = $item->year.'--'.$nextYear.'学年';
            $arr['team'] = $item->team ==1 ? '上学期':'下学期';
            $arr['grade'] = $this->getGradeById($item->grade);
            $arr['class_id'] = $item->class_id.'班';
            $arr['remark'] = $item->remark;
            array_push($items, $arr);
        }
        array_unshift($items, ['姓名', '学年', '学期', '年级', '班级', '班主任备注']);
        return $items;
    }

    /**
     * 生成xls文件  名称叫做班主任管理
     */
    protected function generatorXls($items): void
    {
        $file = time();
        Excel::create('班主任管理', function ($excel) use ($items) {
            $excel->sheet('score', function ($sheet) use ($items) {
                $sheet->rows($items);
            });
        })->store('xls', public_path('xls'));
    }
}
