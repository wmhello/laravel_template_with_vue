<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Import\LeaderImport;
use App\Http\Requests\LeaderRequest;
use App\Http\Requests\LeaderUploadRequest;
use App\Http\Resources\LeaderCollection;
use App\Leader;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;
use \Maatwebsite\Excel\Facades\Excel;

class LeaderController extends Controller
{
    use Result;
    use Tools;

    /**
     * @api {get} /api/leader 获取学校行政列表
     * @apiGroup leader
     *
     * @apiParam {number} [session_id] 学期ID
     * @apiParam {number} [teacher_id] 教师ID
     * @apiSuccessExample 获取学校行政列表，分页显示，每页15条记录,
     * HTTP/1.1 200 OK
     * {
     *  "data": [
     *  {
     *  "id": 13,
     *  "session_id": 3,
     *  "teacher_id": 45,
     *  "leader_type": 2,
     *  "job": "校长",
     *  "remark": null
     *  }
     *  ],
     *  "status": "success",
     *  "status_code": 200,
     *  "links": {
     *  "first": "http://manger.test/api/leader?page=1",
     *  "last": "http://manger.test/api/leader?page=1",
     *  "prev": null,
     *  "next": null
     *  },
     *  "meta": {
     *  "current_page": 1,
     *  "from": 1,
     *  "last_page": 1,
     *  "path": "http://manger.test/api/leader",
     *  "per_page": 15,
     *  "to": 3,
     *  "total": 3
     *  }
     *  }
     *
     */

    public function index(Request $request)
    {
        //

        $pageSize = $request->input('pageSize');
        $pageSize = isset($pageSize) && $pageSize ?$pageSize:10;
        $lists = Leader::LeaderType()->SessionId()->TeacherId()->paginate($pageSize);
      return new LeaderCollection($lists);
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
     * @api {post}/api/leader 新增学校行政信息
     * @apiGroup leader
     *
     * @apiParam {number} session_id 学期ID
     * @apiParam {number} teacher_id 教师ID
     * @apiParam {number=1,2} leader_type 行政类型(1=>中层 2=>学校)
     * @apiParam {string} [job] 职务描述 可选
     * @apiParam {string} [remark] 备注 可选
     * @apiParamExample {object} 请求事例 建立学期 2017-2018上学期:
     * {
     * session_id: 3,
     * teacher_id: 168,
     * leader_type: 1,
     * job: '教务副主任',
     * remark: ''
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
    public function store(LeaderRequest $request)
    {
        //
        $data = $request->only(['teacher_id', 'leader_type', 'job', 'remark']);
        $data['session_id'] = $this->getCurrentSessionId();
        if (Leader::create($data)) {
            return $this->success();
        } else  {
            return $this->error();
        }
    }

    /**
     * @api {get} /api/leader/:id 获取指定的学校行政信息
     * @apiGroup leader
     *
     * @apiParam {number} id 指定的ID
     *
     * @apiSuccessExample 获取指定的学校行政信息
     * HTTP/1.1 200 OK
     * {
     *  "data": [
     *  {
     *  "id": 13,
     *  "session_id": 3,
     *  "teacher_id": 45,
     *  "leader_type": 2,
     *  "job": "校长",
     *  "remark": null
     *  }
     *  ],
     *  "status": "success",
     *  "status_code": 200,
     *  }
     *
     */
    public function show(Leader $leader)
    {
        //
        return new \App\Http\Resources\Leader($leader);
    }


    public function edit(Leader $leader)
    {
        //
    }


    /**
     * @api {post}/api/leader/id 更新指定的学校行政信息
     * @apiGroup leader
     *
     * @apiParam {number} id 指定的ID
     * @apiParam {number} session_id 学期ID
     * @apiParam {number} teacher_id 教师ID
     * @apiParam {number=1,2} leader_type 行政类型(1=>中层 2=>学校)
     * @apiParam {string} [job] 职务描述 可选
     * @apiParam {string} [remark] 备注 可选
     *
     * @apiParamExample {object} 请求事例 建立学期 2017-2018上学期:
     * {
     * session_id: 3,
     * teacher_id: 168,
     * leader_type: 1,
     * job: '教务副主任',
     * remark: '主管学校教学考试与教育信息化'
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
    public function update(LeaderRequest $request, Leader $leader)
    {
        //
        $data = $request->only( ['teacher_id', 'leader_type', 'job', 'remark']);
        $leader->teacher_id = $data['teacher_id'];
        $leader->leader_type = $data['leader_type'];
        $leader->job = $data['job'];
        $leader->remark = $data['remark'];
        if ($leader->save()) {
            return $this->success();
        } else  {
            return $this->error();
        }

    }

    /**
     * @api {delete} /api/leader/:id 删除指定的学校行政信息
     * @apiGroup leader
     * @apiParam {number} id 标识
     * @apiSuccessExample {json} 删除成功:
     * HTTP/1.1 200 OK
     * {
     * "status": "success",
     * "status_code": 200
     * }
     * @apiErrorExample {json} 删除失败
     * HTTP/1.1 404 Not Found
     * {
     * "status": "error",
     * "status_code": 404
     * }
     * @apiErrorExample {json} 指定的ID不存在，无法处理
     * HTTP/1.1 500 Internal Server Error
     * {
     * "status": "error",
     * "status_code": 500
     * }
     */
    public function destroy(Leader $leader)
    {
        //
        if ($leader->delete()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * @api {post} /api/leader/upload 导入行政领导信息
     * @apiGroup leader
     *
     * @apiParam {number} session_id 学期ID
     * @apiParam {string} file 要导入的文件

     * @apiHeaderExample {json} http头部例子
     *     {
     *       "content-type": "multipart/form-data"
     *     }
     *
     * @apiParamExample {object} 请求事例 导入指定学期的行政领导数据:
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
    public function upload( LeaderImport $import,LeaderUploadRequest $request)
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
        $rec = Leader::where('session_id', $sessionId)->count(); // 获得总记录数,因为是所有的数据
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
        $leaderType = (int)request()->input('leader_type');

        $sessionId = (isset($sessionId)&&$sessionId)?$sessionId: $this->getCurrentSessionId();
        $teacherId = (isset($teacherId)&&$teacherId)?$teacherId: null;
        $leaderType = (isset($leaderType)&&$leaderType)?$leaderType: [1,2];
        if (is_numeric($leaderType)) {
            $arr = [];
            array_push($arr,$leaderType);
            $leaderType = $arr;
        }
        $lists = $this->queryData($pageSize, $page,$sessionId, $leaderType,$teacherId);
        $data = $lists->toArray();  // 分页内容
        $items = $this->generatorData($data);
        $this->generatorXls($items);
    }

    protected  function queryData($pageSize = 10, $page = 1, $sessionId, $leaderType,$teacherId){
        // 查询条件  根据姓名或者电话号码进行查询
        $offset = $pageSize * ($page - 1) == 0? 0: $pageSize * ($page - 1);
        $lists = DB::table('leaders')->join('yz_teacher', 'leaders.teacher_id','=', 'yz_teacher.id')
            ->join('sessions', 'leaders.session_id', '=', 'sessions.id' )
            ->select(['yz_teacher.name', 'sessions.year', 'sessions.team','leaders.leader_type', 'leaders.job', 'leaders.remark'])
            ->where('session_id', $sessionId)
            ->whereIn('leader_type', $leaderType)
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
            $arr['leader_type'] = $item->leader_type == 1? '中层':'学校';
            $arr['job'] = $item->job;
            $arr['remark'] = $item->remark;
            array_push($items, $arr);
        }
        array_unshift($items, ['姓名', '学年', '学期', '行政类型', '任职岗位', '岗位备注']);
        return $items;
    }

    /**
     * 生成xls文件  名称叫做员工信息
     */
    protected function generatorXls($items): void
    {
        $file = time();
        Excel::create('行政管理', function ($excel) use ($items) {
            $excel->sheet('score', function ($sheet) use ($items) {
                $sheet->rows($items);
            });
        })->store('xls', public_path('xls'));
    }

    public function test()
    {
        
    }

    public function deleteAll(Request $request)
    {
        $data = $this->deleteByIds($request);
        if ($data) {
            if (Leader::destroy($data['ids'])) {
                return $this->success();
            } else {
                return $this->error();
            }
        }
    }

}
