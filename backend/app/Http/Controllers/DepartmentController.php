<?php

namespace App\Http\Controllers;

use App\Department;
use App\Http\Controllers\Import\DepartmentImport;
use App\Http\Requests\DepartmentRequest;
use App\Http\Requests\DepartmentsUploadRequest;
use App\Http\Resources\DepartmentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DepartmentController extends Controller
{
    use Result,Tools;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @api {get} /api/department 获取学科组长列表
     * @apiGroup department
     *
     * @apiParam {number} [session_id] 学期ID 默认为当前学期
     * @apiParam {number} [teacher_id] 教师ID
     * @apiParam {number=0,1} [leader] 学科组长 0=>备课组长 1=>学科组长 默认包含所有
     * @apiSuccessExample 获取学科组长列表，分页显示，每页15条记录,
     * HTTP/1.1 200 OK
     * {
     * "data": [
     * {
     * "id": 1,
     * "session_id": 3,
     * "teacher_id": 129,
     * "teach_id": 17,
     * "leader": 0,
     * "grade": 1,
     * "remark": "高一语文"
     * }
     * ],
     * "status": "success",
     * "status_code": 200,
     * "links": {
     * "first": "http://manger.test/api/department?page=1",
     * "last": "http://manger.test/api/department?page=1",
     * "prev": null,
     * "next": null
     * },
     * "meta": {
     * "current_page": 1,
     * "from": 1,
     * "last_page": 1,
     * "path": "http://manger.test/api/department",
     * "per_page": 15,
     * "to": 9,
     * "total": 9
     * }
     * }
     */
    public function index(Request $request)
    {
        //
//        $data = $request->only(['session_id', 'page', 'pageSize', 'teacher_id', 'leader']);
//        $pageSize = array_key_exists('pageSize', $data)?$data['pageSize']:15;
//        $teacher_id = array_key_exists('teacher_id', $data)?$data['teacher_id']:null;
//        $session_id = array_key_exists('session_id', $data)?$data['session_id']:$this->getCurrentSessionId();
//        $leader = array_key_exists('leader', $data)?$data['leader']:[1,0];
//        if ($teacher_id && $session_id) {
//            $lists = Department::where('teacher_id', $teacher_id)->where('session_id',$session_id)->whereIn('leader', $leader)->paginate($pageSize);
//        }
//        if (! $teacher_id && $session_id) {
//            $lists = Department::where('session_id',$session_id)->whereIn('leader', $leader)->paginate($pageSize);
//        }
//        if ($teacher_id && !$session_id) {
//            $lists = Department::where('teacher_id', $teacher_id)->whereIn('leader', $leader)->paginate($pageSize);
//        }

        $pageSize = $request->input('pageSize');
        $pageSize = isset($pageSize) && $pageSize ?$pageSize:10;
        $lists = Department::SessionId()->TeacherId()->Grade()->Leader()->paginate($pageSize);
        return new DepartmentCollection($lists);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @api {post} /api/department 创建新的学科组长信息
     * @apiGroup department
     *
     * @apiParam {number} session_id 学期ID
     * @apiParam {number} teacher_id 教师ID
     * @apiParam {number=0,1} leader 学科组长类型(0=>备课组长 1=>学科组长)
     * @apiParam {number=1,2,3} grade 年级
     * @apiParam {number} teach_id 科目  结合科目表
     * @apiParam {string} [remark] 备注 可选
     * @apiParamExample {object} 请求事例 建立学期 2017-2018上学期:
     * {
     * session_id: 3,
     * teacher_id: 168,
     * leader: 0,
     * grade: 1,
     * teach_id: 7
     * remark: '高一信息技术'
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
     * @apiErrorExample {json} 操作失败:
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404
     * }
     */

    public function store(DepartmentRequest  $request)
    {
        //
        $data = $request->only(['teacher_id', 'teach_id', 'leader', 'grade', 'remark']);
        $data['session_id'] = $this->getCurrentSessionId();
        if (Department::create($data)) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */

    /**
     * @api {get} /api/department/:id  获取指定的学科组长信息
     * @apiGroup department
     *
     * @apiParam {number} id 学科组长标识ID
     * @apiSuccessExample 获取指定的学科组长信息
     * HTTP/1.1 200 OK
     * {
     * "data": [
     * {
     * "id": 1,
     * "session_id": 3,
     * "teacher_id": 129,
     * "teach_id": 17,
     * "leader": 0,
     * "grade": 1,
     * "remark": "高一语文"
     * }
     * ],
     * "status": "success",
     * "status_code": 200
     * }
     */

    public function show(Department $department)
    {
        //
        return new \App\Http\Resources\Department($department);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    /**
     * @api {patch} /api/department/:id 更新指定的学科组长信息
     * @apiGroup department
     *
     * @apiParam {number} id 学科组长标识ID
     * @apiParam {number} session_id 学期ID
     * @apiParam {number} teacher_id 教师ID
     * @apiParam {number=0,1} leader 学科组长类型(0=>备课组长 1=>学科组长)
     * @apiParam {number=1,2,3} grade 年级
     * @apiParam {number} teach_id 科目  结合科目表
     * @apiParam {string} [remark] 备注 可选
     * @apiParamExample {object} 请求事例 建立学期 2017-2018上学期:
     * {
     * id：10,
     * session_id: 3,
     * teacher_id: 168,
     * leader: 0,
     * grade: 1,
     * teach_id: 7
     * remark: '信息技术'
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
     * @apiErrorExample {json} 操作失败:
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404
     * }
     */
    public function update(DepartmentRequest $request, Department $department)
    {
        //
        $data = $request->only(['teacher_id', 'teach_id', 'leader', 'grade', 'remark']);
        $department->session_id = $this->getCurrentSessionId();
        $department->teacher_id = $data['teacher_id'];
        $department->teach_id = $data['teach_id'];
        $department->leader = $data['leader'];
        $department->grade = $data['grade'];
        $department->remark = $data['remark'];
        if ($department -> save() ) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    /**
     * @api {delete} /api/department/:id 删除指定的学科组长信息
     * @apiGroup department
     *
     * @apiParam {number} id 学科组长标识ID
     *
     * @apiSuccessExample {json} 操作成功:
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200
     * }

     * @apiErrorExample {json} 操作失败，指定的内容已经删除:
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404
     * }
     */

    public function destroy(Department $department)
    {
        //
        if ($department->delete()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function deleteAll(Request $request)
    {
        $data = $this->deleteByIds($request);
        if ($data) {
            if (Department::destroy($data['ids'])) {
                return $this->success();
            } else {
                return $this->error();
            }
        }
    }

    /**
     * @api {post} /api/department/upload 导入学科组长信息
     * @apiGroup department
     *
     * @apiParam {number} session_id 学期ID
     * @apiParam {string} file 要导入的文件

     * @apiHeaderExample {json} http头部例子
     *     {
     *       "content-type": "multipart/form-data"
     *     }
     *
     * @apiParamExample {object} 请求事例 导入指定学期的学科组长数据:
     * {
     * session_id: 3,
     * file: 'd:/3.xls'
     * }
     *
     * @apiSuccessExample {json} 操作成功:
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200
     * }
     * @apiErrorExample {json} 操作失败:
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404
     * }
     */

    public function upload(DepartmentImport $import, DepartmentsUploadRequest $request)
    {
        $bool = $import->handleImport($import);
        if ($bool) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function exportAll(Request $request) {
        $sessionId =$request->input('session_id');
        $sessionId = isset($sessionId)?$sessionId:$this->getCurrentSessionId();
        $rec = Department::where('session_id', $sessionId)->count(); // 获得总记录数,因为是所有的数据
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
        $tmp = [];
        $leader = request()->input('leader');
        if ($leader === null) {
            $tmp = [0,1];
        } else {
            switch ($leader){
                case 0:
                    array_push($tmp,$leader);
                    break;
                case 1:
                    array_push($tmp,$leader);
                    break;
            }
        }
        $leader = $tmp;
        if (is_numeric($grade)) {
            $arr = [];
            array_push($arr,$grade);
            $grade = $arr;
        }
        var_dump($leader);
        $lists = $this->queryData($pageSize, $page,$sessionId, $grade,$teacherId,$leader);
        $data = $lists->toArray();  // 分页内容
        $items = $this->generatorData($data);
        $this->generatorXls($items);
    }

    protected  function queryData($pageSize = 10, $page = 1, $sessionId, $grade,$teacherId, $leader){
        // 查询条件  根据姓名或者电话号码进行查询
        $offset = $pageSize * ($page - 1) == 0? 0: $pageSize * ($page - 1);
        $lists = DB::table('departments')->join('yz_teacher', 'departments.teacher_id','=', 'yz_teacher.id')
            ->join('sessions', 'departments.session_id', '=', 'sessions.id' )
            ->join('yz_teaching', 'departments.teach_id', '=', 'yz_teaching.id' )
            ->select(['yz_teacher.name', 'sessions.year', 'sessions.team','departments.grade', 'yz_teaching.teaching_name as teach', 'departments.leader', 'departments.remark'])
            ->where('session_id', $sessionId)
            ->whereIn('grade', $grade)
            ->whereIn('leader', $leader)
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
            $arr['teach'] = $item->teach;
            $arr['leader'] = $item->leader === 1?'教研组长':'备课组长';
            $arr['remark'] = $item->remark;
            array_push($items, $arr);
        }
        array_unshift($items, ['姓名', '学年', '学期', '年级', '科目', '组长类型', '备注']);
        return $items;
    }

    /**
     * 生成xls文件  名称叫做教研组长管理
     */
    protected function generatorXls($items): void
    {
        $file = time();
        Excel::create('教研组长管理', function ($excel) use ($items) {
            $excel->sheet('score', function ($sheet) use ($items) {
                $sheet->rows($items);
            });
        })->store('xls', public_path('xls'));
    }

}
