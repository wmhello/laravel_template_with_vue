<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Import\TeachingImport;
use App\Http\Requests\TeachingRequest;
use App\Http\Requests\TeachingUploadRequest;
use App\Http\Resources\Session;
use App\Http\Resources\TeachingCollection;
use App\Teach;
use App\Teaching;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TeachingController extends Controller
{
    use Result, Tools;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $pageSize = $request->input('pageSize');
        $pageSize = isset($pageSize) && $pageSize ?$pageSize:10;
        $lists = Teaching::SessionId()->TeacherId()->Grade()->TeachId()->ClassId()->paginate($pageSize);
        return new TeachingCollection($lists);

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
    public function store(TeachingRequest $request)
    {
        //
        $data = $request->only(['teacher_id', 'teach_id', 'class_id', 'classAll', 'grade', 'hour', 'remark']);
        $data['session_id'] = $this->getCurrentSessionId();
        $classAll = $data['classAll'];
        array_except($data, 'classAll');
        // 循环添加记录
        foreach ($classAll as $item) {
            $data['class_id'] = (int)$item;
            Teaching::create($data);
        }
        return $this->success();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Teaching  $teaching
     * @return \Illuminate\Http\Response
     */
    public function show(Teaching $teaching)
    {
        //
        return new \App\Http\Resources\Teaching($teaching);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Teaching  $teaching
     * @return \Illuminate\Http\Response
     */
    public function edit(Teaching $teaching)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teaching  $teaching
     * @return \Illuminate\Http\Response
     */
    public function update(TeachingUploadRequest $request, Teaching $teaching)
    {
        //
        $data = $request->only(['teacher_id', 'teach_id', 'class_id', 'classAll', 'oldClassAll', 'grade', 'hour', 'remark']);
        $teaching->session_id = $this->getCurrentSessionId();
        $teaching->teacher_id = $data['teacher_id'];
        $teaching->teach_id = $data['teach_id'];
        $teaching->grade = $data['grade'];
        $teaching->remark = $data['remark'];
        $classAll = $data['classAll'];
        $oldClassAll = $data['oldClassAll'];
        $arrTmp = array_diff($oldClassAll,$classAll);
//        foreach ($classAll as $item){
//            $oldItem = [
//              'teacher_id' => $data['teacher_id'],
//              'teach_id' => $data['teach_id'],
//              'grade' => $data['grade'],
//              'session_id' => $teaching->session_id,
//              'class_id' => (int)$item,
//            ];
//            $newItem = [
//                'class_id' => (int)$item,
//                'hour' => $data['hour'],
//                'remark' => $data['remark']
//            ];
//           $bool = Teaching::updateOrCreate($oldItem, $newItem);
//        }

        foreach ($arrTmp as $item) {

          Teaching::where('session_id', $teaching->session_id)
                  ->where('teacher_id', $data['teacher_id'])
                  ->where('grade', $data['grade'])
                  ->where('class_id', (int)$item)
                  ->where('teach_id', $data['teach_id'])
                  ->delete();
        }
        return $this->success();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Teaching  $teaching
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teaching $teaching)
    {
        //
        if ($teaching->delete()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function deleteAll(Request $request)
    {
        $data = $this->deleteByIds($request);
        if ($data) {
            if (Teaching::destroy($data['ids'])) {
                return $this->success();
            } else {
                return $this->error();
            }
        }
    }

    public function upload( TeachingImport $import)
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
        $rec = Teaching::where('session_id', $sessionId)->count(); // 获得总记录数,因为是所有的数据
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
        Excel::create('教学过程管理', function ($excel) use ($items) {
            $excel->sheet('score', function ($sheet) use ($items) {
                $sheet->rows($items);
            });
        })->store('xls', public_path('xls'));
    }

    public function getSelectClassByGrade($id, $grade)
    {

        $item = Teaching::find($id);
        $arr = Teaching::where('teacher_id', $item->teacher_id)
            ->where('session_id', $item->session_id)
            ->where('grade', $grade)
            ->pluck('class_id');
        return $this->successWithData($arr);
    }

    public function test(Request $request)
    {
        $teacher_id = $request->input('teacher_id', 168);
//        if (empty($teacher_id) && $teacher_id !=='0') {
//            echo '内容为空，没有传递参数';
//            dd($teacher_id);
//        } else {
//            echo '内容不为空，传递了参数';
//            dd($teacher_id);
//        }

        $session_id = $request->input('teacher_id',$this->getCurrentSessionId());
        $teach_id = $request->input('teach_id', 7);
        $grade = $request->input('grade', 1);
        $arrGrade = ['zero', 'one', 'two', 'three'];
        $session = \App\Session::find($session_id);
        $totalClass = $session[$arrGrade[$grade]]; // 获取当前学期指定年级的班级数
        $arr = [];
        for ($i = 1; $i<=$totalClass;$i++){
            $key = 'class'.$i;
            $arr[$key] = [
                'disable' => false,
                'label' => $i
            ];
        }
        $tmpArr = [];
        $arr1 = [];
        $grade = 1;
        $tmpArr = Teaching::where('teach_id', $teach_id)
                 ->where('session_id', $session_id)
                 ->where('grade', $grade)
                 ->pluck('class_id');
        foreach ($tmpArr as $item){
            $key = 'class'.$item;
            $arr1[$key] = [
                'disable' => true,
                'label' => $item
            ];
        }
        $result=array_merge($arr,$arr1);
        var_dump($result);
    }

    public function getClassByTeachingId(Request $request, $id)
    {
      $data = Teaching::find($id);
      $teacher_id = $request->input('teacher_id', $data->teacher_id);
      $session_id = $request->input('session_id',$data->session_id);
      $teach_id = $request->input('teach_id', $data->teach_id);
      $grade = $request->input('grade', $data->grade);
      $arrGrade = ['zero', 'one', 'two', 'three'];
      $session = \App\Session::find($session_id);
        $totalClass = $session[$arrGrade[$grade]]; // 获取当前学期指定年级的班级数
        $arr = [];
        for ($i = 1; $i<=$totalClass;$i++){
            $key = 'class'.$i;
            $arr[$key] = [
                'disable' => true,
                'label' => $i
            ];
        }
        $tmpArr = [];
        $arr1 = [];
        $tmpArr = Teaching::where('teach_id', $teach_id)
            ->where('session_id', $session_id)
            ->where('grade', $grade)
            ->where('teacher_id', $teacher_id)
            ->pluck('class_id');
        foreach ($tmpArr as $item){
            $key = 'class'.$item;
            $arr1[$key] = [
                'disable' => false,
                'label' => $item
            ];
        }
        $result=array_values(array_merge($arr,$arr1));
        return $this->successWithData($result);
    }
}
