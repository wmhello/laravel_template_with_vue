<?php

namespace App\Models;

use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LogLogin extends Model
{
    //
    protected $fillable = ['user_id', 'user_name', 'ip', 'type', 'desc'];
    public function saveLoginLog()
    {
        $request = request();
        $time = Carbon::now();
        $strTime = $time->year.'年'.$time->month.'月'.$time->day.'日'.$time->hour.'时'.$time->minute.'分'.$time->second.'秒';
        $data = [
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'ip' => $request->ip(),
            'type' => 'login',
            'desc' => Auth::user()->name.'于'.$strTime.'登录系统',
            'created_at' => $time,
            'updated_at' => $time
        ];
        $this->insert($data);
    }

    public function saveLogoutLog($user)
    {
        //dd($user);
        $request = request();
        $time = Carbon::now();
        $strTime = $time->year.'年'.$time->month.'月'.$time->day.'日'.$time->hour.'时'.$time->minute.'分'.$time->second.'秒';
        $data = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'ip' => $request->ip(),
            'type' => 'logout',
            'desc' => $user->name.'于'.$strTime.'退出系统',
            'created_at' => $time,
            'updated_at' => $time
        ];
        $this->insert($data);
    }
}
