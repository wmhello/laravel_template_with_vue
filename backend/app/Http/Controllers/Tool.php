<?php
namespace  App\Http\Controllers;
use App\Events\DataOperation;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

trait Tool
{
    public function success()
    {
        return response()->json(
            [
                'status' => 'success',
                'status_code' => 200
            ]
            ,200);
    }

    public function successWithInfo($info, $code = 200)
    {
        return response()->json(
            [
                'info' => $info,
                'status' => 'success',
                'status_code' => $code
            ]
            ,$code);
    }

    public function successWithData($data)
    {
        return response()->json([
            'data' => $data,
            'status' => 'success',
            'status_code' => 200
        ], 200);
    }

    public function error()
    {
        return response()->json(
            [
                'status' => 'error',
                'status_code' => 404
            ]
            ,404);
    }

    public function errorWithInfo($info, $code = 404)
    {
        return response()->json(
            [
                'info' => $info,
                'status' => 'error',
                'status_code' => $code
            ] ,$code);
    }

    public function log($type, $route_name, $desc)
    {
        $data = [
            'type' => $type,
            'route_name' => $route_name,
            'desc' => $desc
        ];
        event(new DataOperation($data));
    }

    public function isAdmin()
    {
        $roles = explode(',', Auth::user()->roles);
        return  in_array('admin', $roles);
    }

}