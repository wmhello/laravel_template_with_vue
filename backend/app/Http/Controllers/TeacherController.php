<?php

namespace App\Http\Controllers;

use App\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    use Result;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        //
    }

    /**
     * @api {get} /api/getTeacher 获取教师姓名和id
     * @apiGroup other
     *
     * @apiSuccessExample 返回教师姓名和id列表,
     * HTTP/1.1 200 OK
     * {
     *  "data": [
     *     {
     *       "id": 2 // 整数型  教师标识
     *       "name":  '测试'  //字符型 教师姓名
     *     }
     *   ],
     *  "status": "success",
     *  "status_code": 200
     * }
     *
     */
    public function getTeacher()
    {
        $data = Teacher::select('id','name')->get()->toArray();
        return $this->successWithData($data);
    }

    public function getTeacherByTeachingId(Request $request)
    {
        $teaching_id = $request->input('teaching_id');
        $teachers = Teacher::select('id', 'name')->where('teaching_id', $teaching_id)->get();
        return $this->successWithData($teachers);
    }
}
