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
        if(!in_array($this->params['code'],$this->getMobileCode()))return $this->respondWithData(10003);
        //check user is already
        //if not exists saving
        $result = $this->user->firstOrCreate(['mobile' => $this->params['mobile']]);
        if($result->status == 2) return $this->respondWithData(20010);
        return $this->respondWithData(200,['id' => encrypt($result->id) , 'mobile' => $result->mobile ]);
    }
}