<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class LogController extends Controller
{
    //
    use Result;

    public function index(Request $request)
    {
        $pageSize = $request->input('pageSize', 10);
        $page = $request->input('page', 1);
        $data = DB::table('log_logins')->select(['id', 'user_name', 'type', 'desc'])->paginate($pageSize);
        return Response()->json($data);
    }

}
