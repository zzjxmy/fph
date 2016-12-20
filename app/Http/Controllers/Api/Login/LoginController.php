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

class LoginController extends Controller
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($this->request);
    }

    public function handle()
    {
        $v = $this->verifyParams($this->params, [
            'mobile' => 'required',
            'code'   => 'required',
        ]);
        if($v) return $this->respondWithData(10002);
        $codeArray = $this->getMobileCode();
        $mobileCode = in_array($this->params['code'],$codeArray);
        if(!$mobileCode)return $this->respondWithData(10003);
    }

    /**
     * @return array
     * 获取服务端验证码
     * mihailong
     */
    private function getMobileCode(){
        $url = config('app.SmsUrl').'user/getSmsCode';
        $data = [
            'token' =>$this->params['token'],
            'mobile'=>$this->params['mobile'],
        ];
        $result = json_decode(getApiJson($url,$data),true);
        if($result && $result['code']=10000){
            return $result['data'];
        }else{
            return [];
        }
    }
}