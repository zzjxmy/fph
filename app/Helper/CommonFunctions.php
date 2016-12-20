<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 15-7-30
 * Time: 下午7:03
 */

namespace App\Helper;


class CommonFunctions {

    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    //隐藏手机号码部分数字
    function getPartMobile($mobile,$startNum,$endNum){
        $newMobile  =   '';
        if(is_numeric($startNum) && is_numeric($endNum) && $mobile){
            $endNum =   0 - $endNum;
            if($mobile){
                $newMobile = substr($mobile, 0, $startNum) . '****' . substr($mobile, $endNum);
            }
        }
        return $newMobile;
    }

    /**
     * @param $type {D:订单,T:退单,P:赔偿单}
     * @param $uid  用户ID
     * @return string
     */
    function getOrderNum($type , $uid) {
        $str = $type.str_pad($uid,7,'0',STR_PAD_LEFT).date('ymdhis').date('y').rand(10,99);
        return $str;
    }
}