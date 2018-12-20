<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class LogController extends Controller
{
    //
    use Result,Tools;

    public function index(Request $request)
    {
        $pageSize = $request->input('pageSize', 10);
        $page = $request->input('page', 1);
        $data = DB::table('log_logins')->select(['id', 'user_name', 'type', 'desc'])
            ->when(!$this->isAdmin(), function($query) {
                return $query->where('user_id', Auth::user()->id);
            })
            ->latest()->paginate($pageSize);
        return Response()->json($data);
    }

    // 操作日志记录
    public function show(Request $request){
        $pageSize = $request->input('pageSize', 10);
        $page = $request->input('page', 1);
        $data = DB::table('log_works')->select(['id', 'user_name', 'type', 'desc'])
            ->when(!$this->isAdmin(), function($query) {
                return $query->where('user_id', Auth::user()->id);
            })
            ->paginate($pageSize);
        return Response()->json($data);
    }

}
