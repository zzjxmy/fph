<?php
/**
 * CopyRight © 2016
 * @desc 名片查看
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     ShareInfoController.php（类名/函数名）
 * @date:     2016-12-29
 * @directory: /app/classes/Controller/fph/ShareInfoController.php
 */

namespace App\Http\Controllers\Api\Share;

use App\Http\Controllers\Controller;
use App\Model\Dao\IssueDao;
use App\Model\Dao\UserDao;
use PFinal\Wechat\Kernel;
use PFinal\Wechat\Service\JsService;

class ShareInfoController extends Controller {
    public function handle(){
        if(!is_login()) return $this->respondWithData(10000);
        //get user info
        $uid = decrypt($this->request->input('uid'));
        $user = UserDao::where('id',$uid)->where('status',1)->with(['city'=>function($query){
            $query->select('id','name');
        }])->first(['id','nickname','headimgurl','sex','city_id','sign','company_id']);
        if(!$user) return $this->respondWithData(20003);
        
        //count publish
        $count = 0;
        $count += $data['supplyAndDemand'] = IssueDao::where('uid',$uid)->where('class',1)->count();
        $count += $data['bulk'] = IssueDao::where('uid',$uid)->whereIn('class',[2,3])->count();
        $count += $data['technology'] = IssueDao::where('uid',$uid)->whereIn('class',[4,5,6,7])->count();
        
        //share
        $share['title'] = '我在81地产等你';
        $share['img'] = $user->headimgurl;
        $share['content'] = '我是'.$user->nickname.'，我在81地产免费发布了'.$count.'个项目，快来看看吧 ';
        $share['url'] = url('/share/share/index?id='.$uid);
        
        //获取微信js SignPackage
        Kernel::init(config('wechat'));
        $signPackage = JsService::getSignPackage();
        return $this->respondWithData(200,[
            'user' => $user ,
            'publishCount' => $data ,
            'share' => $share,
            'signPackage'=>$signPackage
        ]);
    }
}