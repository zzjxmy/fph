<?php
/**
 * CopyRight © 2016
 * @desc 这里是控制器描述
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     UserController.php（类名/函数名）
 * @date:     2016-12-21
 * @directory: /app/classes/Controller/fph/UserController.php
 */

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Model\Bll\City\CityBll;
use App\Model\Dao\UserDao;
use Illuminate\Http\Request;


class UserController extends Controller {
    
    public $request,$uid,$cityBll;
    
    /**
     * UserController constructor.
     * @param Request $request
     * @param CityBll $cityBll
     * @return string
     */
    public function __construct ( Request $request ,CityBll $cityBll )
    {
        $this->request = $request;
        $this->cityBll = $cityBll;
        parent::__construct($request);
    }
    
    /**
     * update user info
     * @param $cityBll
     * @return $this
     */
    public function handle(){
        //decrypt UID
        if(!is_login())return $this->respondWithData(20003);
        //get user info
        $userInfo = UserDao::where('id',$this->uid)->first(['id','nickname','mobile','headimgurl','sex','city_id','sign']);
        if(!$userInfo)return $this->respondWithData(20003);
        
        switch ($this->request->input('type')){
            case 'sex': //update sex
                $sex = $this->request->input('sex',1);
                if(!in_array($sex,[1,2])) return $this->respondWithData(20004);
                $userInfo->sex = $sex;
                break;
            case 'avatar': //update avatar
                $avatar = $this->request->input('avatar');
                if(empty($avatar) || !in_array($avatar,config('app.avatarList'))) return $this->respondWithData(20008);
                //get avatar list
                $userInfo->headimgurl = $avatar;
                break;
            case 'mobile': //update mobile or bind mobile
                $mobile = $this->request->input('mobile');
                //check code
                if(!in_array($this->request->input('code'),$this->getMobileCode())) return $this->respondWithData(10003);
                //check mobile is exists
                if(!is_mobile($mobile)) return $this->respondWithData(20009);
                if($mobile == $userInfo->mobile) return $this->respondWithData(20005);
                if(UserDao::where('mobile',$mobile)->count()) return $this->respondWithData(20001);
                $userInfo->mobile = $mobile;
                break;
            case 'username': //update username
                $nickname = $this->request->input('username');
                if(empty($nickname)) return $this->respondWithData(20006);
                $userInfo->nickname = $nickname;
                break;
            case 'city': //update city
                $city = intval($this->request->input('city'));
                $userInfo->city_id = $city;
                break;
            case 'signature': //update signature
                $userInfo->signature = htmlspecialchars($this->request->input('signature'));
                break;
            case 'info': //get userInfo
                $data = $userInfo->toArray();
                unset($data['id']);
                return $this->respondWithData(200,[
                    'user' => $data,
                    'cityName' => $this->cityBll->getCityNameByCityId($userInfo->city_id),
                    'avatarList' => config('app.avatarList') ,
                    'cityList' => $this->cityBll->getCitybByPid()
                ]);
                break;
            case 'positionAndcardimgurl': //job and business card img url save
                
                $position = $this->request->input('position');
                if(empty($position)) return $this->respondWithData(20006);
                $userInfo->position = $position;
                
                $cardimgurl = $this->request->input('cardimgurl');
                if(empty($cardimgurl)) return $this->respondWithData(20006);
                $userInfo->cardimgurl = $cardimgurl;
                
                break;
            case 'default':
                return $this->respondWithData(10001,['message' => 'type is not defined']);
        }
        if($userInfo->save()) return $this->respondWithData(200);
        return $this->respondWithData(20007);
    }
    
}