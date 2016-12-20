<?php

/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 16-12-12
 * Time: 下午3:38
 */

namespace App\Http\Controllers\Api\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginSMSController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($this->request);
    }

    /**
     * @return $this
     * 获取短信验证码
     * mihailong
     */
    public function handle()
    {
        $v = $this->verifyParams($this->params, [
            'mobile' => 'required',
        ]);
        if($v) return $this->respondWithData(10002);
        $url = config('app.SmsUrl').'sendsms';
        $data = [
            'token' =>$this->params['t'],
            'mobile'=>$this->params['mobile'],
        ];
        $result = json_decode(getApiJson($url,$data),true);
        if($result && $result['code']=10000){
            return $this->respondWithData(200);
        }else{
            return $this->respondWithData(10000);
        }

    }
}