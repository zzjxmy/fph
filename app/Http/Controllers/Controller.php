<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

class Controller extends BaseController
{
    public $request, $params;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->params = $this->request->all();
    }

    public function verifyParams($params, $verifyType)
    {
        $errors = [];
        if(!$params){
            return 10002;
        }

        $v = Validator::make($params, $verifyType);
        if ($v->fails())
        {
            foreach($v->errors()->toArray() as $key => $val)
            {
                $errors[] = $key . '.' . $val[0];
            }
            return 10002;
        } else {
            return $errors;
        }
    }

    /**
     * @param $error 错误/正确代码、信息
     * @param string $returnValue   返回数据字符串
     * @param array $returnObject   返回数据对象
     * @return $this
     */
    public function respondWithData($code, $value = "")
    {
        $error = config('code');
        $data = [
            'code'     =>  $code,
            'msg'      =>  $error[$code]['msg'],
            'data'     =>  !$value ? "" : $value,
        ];
        $result = json_encode($data);
        return (new Response($result, 200))->header("Content-Type", 'application/json');
    }

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

    protected function token(){
        return md5(config('app.tokenKey') . date('Ymd')) ;
    }
}
