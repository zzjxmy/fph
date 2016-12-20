<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 15/7/14
 * Time: 上午11:21
 */

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;


class UserMiddleware
{

    public function verifyUserToken($token)
    {
        $stringLen = strlen($token);
        if ($stringLen < 6)
        {
            return false;
        }
        for ($i = 0; $i <=4; $i++)
        {
            if (is_numeric(substr($token, $i, 1)))
            {
                $number[] = intval(substr($token, $i, 1));
            } else {
                return false;
            }
        }
        $type = (is_numeric($number[0]) & ($number[0] & 1)) ? true : false; //奇数 true 偶数 false
        $baseKey = Config::get('app.userTokenKey');
        if ($type)  //奇数
        {
            $loclKey = md5(substr($baseKey, $number[1], $number[2]));
            $localSub = substr($loclKey, 0, $number[3]);
            $postSub = substr($token, 5, $number[3]);
            if ($postSub !== $localSub)
            {
                return false;
            }
        } else {
            $loclKey = md5(substr($baseKey, $number[2], $number[1]));
            $localSub = substr($loclKey, -$number[3]);
            $postSub = substr($token, 5, $number[3]);
            if ($postSub !== $localSub)
            {
                return false;
            }
        }
        $uid = substr($token, 5 + $number[3], $number[4]);
        $cacheToken = Cache::get($uid);
        if ($cacheToken && $cacheToken == $token)
        {
            return false;
        }
        Cache::forever($uid, $token);
        return $uid;
    }
}