<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/30 0030
 * Time: 22:57
 */

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Session;
use Carbon\Carbon;

trait Tools
{
   public function getCurrentSessionId(){
       $date = Carbon::now();
       $year = $date->year;
       $team =1;
       $day = $date->day;
       $month = $date->month;
       switch ($month){
           case 2:
              if ($day>=25) {
                  $team = 2;
              } else {
                  $team = 1;
              }
              break ;
           case 3:
           case 4:
           case 5:
           case 6:
           case 7:
            $team = 2;
            break;
           case 8:
               if ($day>=25) {
                   $team = 1;
               } else {
                   $team = 2;
               }
               break ;
           default:  // 默认为1
            $team =1;
           break;
       }
       if ($team == 2 || $month <=7) {  // 核算年份 1-7月份、8月份（8月25日以下为上一年）
           $year--;
       }
       $session_id = Session::where('year', $year) ->where('team', $team)->value('id');
       return $session_id;
   }

    public function getGradeById($id)
    {
        $grades = ['', '高一', '高二', '高三'];
        return $grades[$id];
    }

}