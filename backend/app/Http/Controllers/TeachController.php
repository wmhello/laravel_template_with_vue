<?php

namespace App\Http\Controllers;

use App\Teach;
use Illuminate\Http\Request;

class TeachController extends Controller
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
     * @param  \App\Teach  $teach
     * @return \Illuminate\Http\Response
     */
    public function show(Teach $teach)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Teach  $teach
     * @return \Illuminate\Http\Response
     */
    public function edit(Teach $teach)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teach  $teach
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teach $teach)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Teach  $teach
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teach $teach)
    {
        //
    }
    /**
     * @api {get} /api/getTeach 获取学科名称和id
     * @apiGroup other
     *
     * @apiSuccessExample 返回学科和id列表,
     * HTTP/1.1 200 OK
     * {
     *  "data": [
     *     {
     *       "id": 2 // 整数型  学科标识
     *       "name":  '体育'  //字符型 教学科目
     *     }
     *   ],
     *  "status": "success",
     *  "status_code": 200
     * }
     *
     */
    public function getTeach()
    {
       $data = Teach::select('id', 'teaching_name as name')->get();
       return $this->successWithData($data);
    }
}
