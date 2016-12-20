<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 7/15/16
 * Time: 6:05 PM
 */


if (! function_exists('get_flip_status')) {
    /**
     * @param $startTime
     * @param $endTime
     * @return int
     */
    function get_flip_status($startTime, $endTime) {
        if (time() < $startTime) {
            $status = 1;     //末开始
        } elseif (time() >= $startTime && time() <= $endTime) {
            $status = 2;     //进行中
        } elseif (time() > $endTime) {
            $status = 3;     //已结束
        }
        return $status;
    }
}


if(! function_exists('getApiJson')){
    /**
     * @param $url
     * @param $data
     * @param string $method
     * @return mixed
     */
    function getApiJson($url,$data,$method = 'POST'){
        $ch = curl_init();
        if($method == 'GET'){
            $url .= '?';
            foreach ($data as $key=>$value){
                $url .= $key.'='.$value.'&';
            }
        }
        curl_setopt($ch, CURLOPT_URL, rtrim($url,'&'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        if($method == 'POST'){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}

if(! function_exists('formatTime')){

    function formatTime($time){
        $dur = time() - $time;
        switch($dur){
            case $dur<5*60:
                $str = '刚刚';
                break;
            case $dur<60*60:
                $str = floor($dur/60).'分钟前';
                break;
            case $dur<24*60*60:
                $str = floor($dur/3600).'小时前';
                break;
            case $dur<7*24*60*60:
                $str = floor($dur/86400).'天前';
                break;
            default:
                $str = date('Y-m-d H:i',$time);
        }
        return $str;
    }

}

if(! function_exists('is_mobile')){
    function is_mobile($mobile){
        return preg_match('/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/',$mobile);
    }
}

if(!function_exists('encrypt')){
    function encrypt($str = ''){
        if(empty($str))return false;
        return Crypt::encrypt($str);
    }
}

if(!function_exists('decrypt')){
    function decrypt($str = ''){
        if(empty($str))return false;
        return Crypt::decrypt($str);
    }
}

if(!function_exists('is_login')){
   function is_login(){
       if(session('user'))return true;
       $uid = Request::input('uid');
       if($uid && Crypt::decrypt($uid)) return true;
       return false;
   }
}