<?php
/**
 * CopyRight © 2016
 * @desc 微信登录
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     WeChatController.php（类名/函数名）
 * @date:     2016-12-21
 * @directory: /app/classes/Controller/fph/WeChatController.php
 */

namespace App\Http\Controllers\Web\WeChat;


use App\Http\Controllers\Controller;
use App\Model\Dao\UserDao;
use PFinal\Wechat\Kernel;
use PFinal\Wechat\Service\OAuthService;

class WeChatController extends Controller {
    
    public function Login(){
        //init config
        Kernel::init(config('wechat'));
        //get user info need user access
        $userInfo = OAuthService::getUser();
        if(isset($userInfo['errcode']) || !isset($_GET['code'])) return view('error.error',['message' => config('code.20011')]);
        if(isset($userInfo['openid'])){
            //select user by openid
            $user = UserDao::where('openid',$userInfo['openid'])->first();
            if(!$user){
                //save
                $user = UserDao::create([
                    'openid' => $userInfo['openid'],
                    'nickname' => $userInfo['nickname'],
                    'headimgurl' => $userInfo['headimgurl'],
                    'sex' => $userInfo['sex'],
                ]);
                //redirect home view
                if(!$user) return view('error.error',['message' => config('code.20011')]);
            }else{
                if(!$user['status'] == 2) return view('error.error',['message'=>config('code.20010')]);
            }
            return view('wechat.setUsername',['uid' => encrypt($user->id)]);
        }
    }
}