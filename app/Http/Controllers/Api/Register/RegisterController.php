<?php
/**
 * Created by PhpStorm.
 * User: zhangzhijian
 * Date: 2016/12/20
 * Time: 14:58
 */

namespace App\Http\Controllers\Api\Register;

use App\Model\Dao\UserDao;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller{
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
        $count = $this->user->where('mobile',$this->params['mobile'])->count();
        if($count) return $this->respondWithData(20001);
        //if not exists saving
        $result = $this->user->create(['mobile' => $this->params['mobile']]);
        if(!$result) return $this->respondWithData(20002);
        return $this->respondWithData(200,['id' => encrypt($result->id) , 'mobile' => $result->mobile ]);
    }


}