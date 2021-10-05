<?php


namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Validator;

trait Validate
{

    /**
     * 新增数据校验，如果校验通过，则显示为true，否则返回错误信息
     * @param $data
     * @return bool|string
     */
    public function validateStore($data)
    {

        $validator = Validator::make($data, $this->storeRule(), $this->message());
        if ($validator->fails()) {
             $errors = $validator->errors()->toArray();
             $msg = $this->tranMessage($errors);
             return $msg;
        }else {
            return true;
        }

    }

    public function validateUpdate($data, $id)
    {

        $validator = Validator::make($data, $this->updateRule($id), $this->message());
        if ($validator->fails()) {
             $errors = $validator->errors()->toArray();
             $msg = $this->tranMessage($errors);
             return $msg;
        }else {
            return true;
        }

    }

    /**
     *  错误信息转换
     * @param $errors
     * @return string
     */
    protected function tranMessage($errors)
    {
        $tips = '';
        foreach ($errors as $k => $v) {
            foreach ($v as $v1) {
                $tips .= $v1.',';
            }
        }
        $end = strrpos($tips,',');
        $tips = substr($tips, 0, $end);
        return $tips;
    }
}
