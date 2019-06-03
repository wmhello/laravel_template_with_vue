<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogLoginCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class LogController extends Controller
{
    //

    // 登录日志记录
    public function index(Request $request)
    {
        $pageSize = $request->input('pageSize', 10);
        $page = $request->input('page', 1);
        $userName = $request->input('user_name');
        $type = $request->input('type');
        $createdAt = $request->input('created_at');
        if (isset($createdAt)) {
            $createdAt = Carbon::createFromTimestamp($createdAt);  // 根据时间戳来建立
        }
        $data = DB::table('log_logins')->select(['id', 'user_name', 'type','ip', 'desc', 'created_at'])
            ->when(!$this->isAdmin(), function($query) {
                return $query->where('user_id', Auth::user()->id);
            })
            ->when($userName, function($query) use($userName) {
                return $query->where('user_name', $userName);
            })
            ->when($type, function($query) use($type) {
                return $query->where('type', $type);
            })
            ->when($createdAt, function($query) use($createdAt) {
                return $query->whereDate('created_at', $createdAt);
            })
            ->paginate($pageSize);
        return new LogLoginCollection($data);
    }

    // 操作日志记录
    public function show($id = null){
        $request = request();
        $pageSize = $request->input('pageSize', 10);
        $page = $request->input('page', 1);
        $userName = $request->input('user_name');
        $type = $request->input('type');
        $createdAt = $request->input('created_at');
        if (isset($createdAt)) {
            $createdAt = Carbon::createFromTimestamp($createdAt);  // 根据时间戳来建立
        }
        $data = DB::table('log_works')->select(['id', 'user_name', 'type','ip', 'desc', 'created_at'])
            ->when(!$this->isAdmin(), function($query) {
                return $query->where('user_id', Auth::user()->id);
            })
            ->when($userName, function($query) use($userName) {
                return $query->where('user_name', $userName);
            })
            ->when($type, function($query) use($type) {
                return $query->where('type', $type);
            })
            ->when($createdAt, function($query) use($createdAt) {
                return $query->whereDate('created_at', $createdAt);
            })
            ->paginate($pageSize);
        return new LogLoginCollection($data);
    }

}
