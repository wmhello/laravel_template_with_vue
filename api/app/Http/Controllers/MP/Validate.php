<?php


namespace App\Http\Controllers\MP;
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

    protected function checkIdCard($idcard){

        $City = array(11=>"北京",12=>"天津",13=>"河北",14=>"山西",15=>"内蒙古",21=>"辽宁",22=>"吉林",23=>"黑龙江",31=>"上海",32=>"江苏",33=>"浙江",34=>"安徽",35=>"福建",36=>"江西",37=>"山东",41=>"河南",42=>"湖北",43=>"湖南",44=>"广东",45=>"广西",46=>"海南",50=>"重庆",51=>"四川",52=>"贵州",53=>"云南",54=>"西藏",61=>"陕西",62=>"甘肃",63=>"青海",64=>"宁夏",65=>"新疆",71=>"台湾",81=>"香港",82=>"澳门",91=>"国外");
        $iSum = 0;
        $idCardLength = strlen($idcard);
        //长度验证
//        if(!preg_match('/^d{17}(d|x)$/i',$idcard) and!preg_match('/^d{15}$/i',$idcard))
//        dd(preg_match('/^d{17}(d|x|X)$/i',$idcard));
        // 长度只能为18位或者15为
        if ($idCardLength !== 18 && $idCardLength !== 15){
//            dd('长度必须为18位或者15位');
            return false;
        }
        if (preg_match('/^d{17}(d|x|X)$/i',$idcard)!== 0)
        {
//            dd('18长度校验不过');
            return false;
        } else if (preg_match('/^d{15}$/i',$idcard)!== 0)
        {
//            dd('15长度校验不过');
            return false;
        }

        //地区验证
        if(!array_key_exists(intval(substr($idcard,0,2)),$City))
        {
//            dd('地区校验不过');
            return false;
        }
        // 15位身份证验证生日，转换为18位
        if ($idCardLength === 15)
        {
          $sBirthday = '19'.substr($idcard,6,2).'-'.substr($idcard,8,2).'-'.substr($idcard,10,2);
          $d = new \DateTime($sBirthday);
          $dd = $d->format('Y-m-d');
          if($sBirthday != $dd)
          {
            return false;
          }
          $idcard = substr($idcard,0,6)."19".substr($idcard,6,9);//15to18
          $Bit18 = $this->getVerifyBit($idcard);//算出第18位校验码
          $idcard = $idcard.$Bit18;
        }
        // 判断是否大于2078年，小于1900年
        $year = substr($idcard,6,4);
        if ($year > 2078 && $year < 1900 )
        {
          return false;
        }

        //18位身份证处理
        $sBirthday = substr($idcard,6,4).'-'.substr($idcard,10,2).'-'.substr($idcard,12,2);
        $d = new \DateTime($sBirthday);
        $dd = $d->format('Y-m-d');
        if($sBirthday != $dd)
        {
//             dd('出生年月校验不过');
            return false;
        }
        //身份证编码规范验证
        $idcard_base = substr($idcard,0,17);
        if(strtoupper(substr($idcard,17,1)) !== $this->getVerifyBit($idcard_base))
        {
//            dd('编码规范校验不过');
            return false;
        }
        return true;
    }

    protected  function isPhone($phone) {
        $result = false;
        if(preg_match("/^1[3456789]\d{9}$/", $phone)){
           //这里有无限想象
            $result = true;
        }
        return $result;
    }

    protected function checkPassword($password) {
       // 密码长度必须大于8个字符，含有小写和特殊符号
        $result = false;
        if (strlen($password)>=8 && preg_match("/[a-z]/", $password)>=0 && preg_match("/[@#%$&]/", $password)>=0) {
            $result = true;
        }
        return $result;
    }


     // 计算身份证校验码，根据国家标准GB 11643-1999
     protected  function getVerifyBit($idcard_base)
     {
        if(strlen($idcard_base) != 17)
        {
          return false;
        }
        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4','3', '2');
        $checksum = 0;
        for ($i = 0; $i < strlen($idcard_base); $i++)
        {
          $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return $verify_number;
     }
}
