<?php

namespace App\Http\Controllers\MP;

use App\Models\MpRole;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ScanOpt;

class CommonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function reg_code()
    {
        $guid = request('guid', '');
        $action = request('action', '');
        $open_id = request('open_id', '');
        if ($guid && $action && $open_id) {
            $item = ScanOpt::where('guid', $guid)->first();
            $item->action = $action;
            $item->open_id = $open_id;
            $item->updated_at = Carbon::now();
            $item->save();
            return $this->successWithInfo('小程序端扫描验证操作已经完成，请在PC端执行相关操作');
        } else {
            return $this->errorWithInfo('扫描验证出错',  400);
        }
    }

    public function subject()  // 获取学科
    {
        $school_id = $this->getSchoolIdByOpenid();
        $type_no = School::find($school_id)->type_no;
        $params = floor((int)$type_no / 100);
        $result = DB::table('subject')->where('type_no', $params)->orderBy('order', 'asc')->select(['name as label', 'id as value'])->get();
        return $this->successWithData($result);
    }
    
    public function tech()  // 获取职称等
    {
        $params = (int)request('identify', '1');
        $result = DB::table('tech_category')->where('class', $params)->orderBy('order', 'asc')->get();
        return $this->successWithData($result);
    }
    

    public function getAllArea()
    {
        $sql = <<<STR
select no, name, parent_no as p_no , 1 as level from province
union 
select no, name, parent_no as p_no, 2 as level from city
union 
select no, name, parent_no as p_no, 3 as level from area
STR;
        $result = DB::select($sql);
        $arr = [];
        $t = (array)$result[0];
        $index = 0;
        foreach($result as $k => $v) {
            $v = (array)$v;
            $v['label'] = $v['name'];
            $v['value'] = $v['no'];
            if ($t['level'] === $v['level']) {
                $v['extra'] = $index;
                $index ++;
                $t = $v;
            } else {
                  $index = 0;
                  $v['extra'] = $index;
                  $index ++;
                  $t = $v;
            }
            $arr[] = $v;
        }
        $result = $this->get_tree($arr, '100000');

        return $this->successWithData($result);
    }

    public function nation()  // 获取民族接口
    {
        $result = DB::table('nation')->select(['id', 'nation as text', 'nation as label', 'nation as value'])->get();
        return $this->successWithData($result);
    }

    public function community()
    {

        $sql = <<<STR
select no, name, '530400' as p_no, 1 as level from area where parent_no = 530400
union 
select no, name, area_no as p_no, 2 as level from streets where area_no in (select no from area where parent_no = 530400)
union 
select no, name, street_no as p_no, 3 as level from villages where street_no in (select no from streets where area_no in (select no from area where parent_no = 530400)
)
STR;
        $result = DB::select($sql);
        $arr = [];
        $t = (array)$result[0];
        $index = 0;
        foreach($result as $k => $v) {
            $v = (array)$v;
            $v['label'] = $v['name'];
            $v['value'] = $v['no'];
            if ($t['level'] === $v['level']) {
                $v['extra'] = $index;
                $index ++;
                $t = $v;
            } else {
                  $index = 0;
                  $v['extra'] = $index;
                  $index ++;
                  $t = $v;
            }
            $arr[] = $v;
        }
           $result = $this->get_tree($arr);
           return $this->successWithData($result);
    }


    public function area()
    {
        $area_no  = request('area_no', '530402');
        $sql = <<<STR
select no, name, area_no as p_no, 2 as level from streets where area_no = :area_no
union 
select no, name, street_no as p_no, 3 as level from villages where street_no in (select no from streets where area_no  = :area_no)
STR;
        $sql = str_replace(':area_no', $area_no, $sql);
        $result = DB::select($sql);
        $arr = [];
        $t = (array)$result[0];
        $index = 0;
        foreach($result as $k => $v) {
            $v = (array)$v;
            $v['label'] = $v['name'];
            $v['value'] = $v['no'];
            if ($t['level'] === $v['level']) {
                $v['extra'] = $index;
                $index ++;
                $t = $v;
            } else {
                  $index = 0;
                  $v['extra'] = $index;
                  $index ++;
                  $t = $v;
            }
            $arr[] = $v;
        }
           $result = $this->get_tree($arr, $area_no, 'no', 'p_no', 'children', 2);
           return $this->successWithData($result);
    }




    protected function get_tree($arr, $pid = '530400', $id = 'no', $pname = 'p_no', $child = 'children',$deep=1)
    {
        $tree = array();
        foreach ($arr as $k => $value) {
            if ($value[$pname] == $pid) {
                $value[$child] = $this->get_tree($arr, $value[$id], $id = 'no', $pname = 'p_no', $child = 'children', $deep + 1);
                if ($value[$child] == null) {
                    unset($value[$child]);
                }
                $value['deep'] = $deep;
                $tree[] = $value;
            }
        }
        return $tree;
    }


    public function getStuType()
    {
        $result = DB::table('stu_type')->select(['name as text', 'name as label'])->get();
        return $this->successWithData($result);
    }


}
