<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/13 0013
 * Time: 09:35
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }
}