<?php

/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 16-12-12
 * Time: 下午3:38
 */

namespace App\Http\Controllers\Api\Login;

use App\Model\Dao\UserDao;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{
    public $request,$user;

    public function __construct(Request $request,UserDao $userDao)
    {
        $this->request = $request;
        $this->user = $userDao;
        parent::__construct($this->request);
    }

    public function handle()
    {
        $v = $this->verifyParams($this->params, [
            'mobile' => 'required|numeric',
            'code'   => 'required|numeric',
        ]);
        if($v || !is_mobile($this->params['mobile'])) return $this->respondWithData(10002);
        $codeArray = $this->getMobileCode();
        $mobileCode = in_array($this->params['code'],$codeArray);
        if(!$mobileCode)return $this->respondWithData(10003);
        //check user is already
        //if not exists saving
        $result = $this->user->firstOrCreate(['mobile' => $this->params['mobile']]);
        return $this->respondWithData(200,['id' => encrypt($result->id) , 'mobile' => $result->mobile ]);
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