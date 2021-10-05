<?php


namespace App\Http\Controllers\Admin;


trait FileHandle
{

        /**
         * 根据map表，处理数据
         * @param $data
         */
        protected function handleItem($data, $type = 'export'){
            $arr = [];
            if ($type === 'export'){
                foreach ($this->map as $key => $item){
                    if (!isset($data[$item])){
                        continue;
                    }
                    $arr[$key] = $data[$item];
                }
            }
            if ($type === 'import'){
                foreach ($this->map as $key => $item){
                    if (!isset($data[$key])){
                        continue;
                    }
                    $arr[$item] = $data[$key];
                }
            }
            return $arr;
        }


                protected function importHandle($arrData){
//        1. 要对每一条记录进行校验

//        2. 根据校验的结果，计算出可以导入的条数，以及错误的内容

        $error = []; // 错误的具体信息
        $isError = false;  // 是否存在信息错误
        $successCount = 0; // 统计数据导入成功的条数
        $errorCount = 0;  // 出错的条数
        $arr = [];  // 正确的内容存储之后，返回数据
        foreach ($arrData as $key => $item) {
            $data = $this->handleItem($item, 'import');
            $data['created_at'] = Carbon::now();
            // 可以根据需要，进一步处理数据
            $this->validatorData($item,$data,$error, $isError ,$successCount, $errorCount,$arr);
        }
        return [
            'successData' => $arr,
            'errorData' => $error,
            'isError' => $isError,
            'errorCount' => $errorCount,
            'successCount' => $successCount,
        ];
    }

        protected function validatorData($item, $data, &$error, &$isError ,&$successCount, &$errorCount,&$arr){
        if (method_exists($this, 'message')){
            $validator = Validator::make($data,$this->rulesStore(),$this->message());
        } else {
            $validator = Validator::make($data,$this->rulesStore());
        }
        if ($validator->fails()){
            // 获取相关的错误信息，并且把错误信息单独存放
            $errors = $validator->errors($validator);
            $tips = '';
            foreach ($errors->all() as $message){
                $tips .= $message.',';
            }
            $tips = substr($tips,0,strlen($tips)-1);
            // 状态信息
            $item['错误原因'] = $tips;
            $error[] = $item;
            $isError = true;
            $errorCount ++;
        } else {
            // 没有出错的，我们先存在正确的数组
            $arr[] = $data;
            $successCount ++;
        }
    }

}


