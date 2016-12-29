<?php
/**
 * CopyRight © 2016
 * @desc 分享之后的名片查看
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     ShareController.php（类名/函数名）
 * @date:     2016-12-29
 * @directory: /app/classes/Controller/fph/ShareController.php
 */

namespace App\Http\Controllers\Api\Share;

use App\Http\Controllers\Controller;
use App\Model\Dao\IssueDao;
use App\Model\Dao\UserDao;

class ShareController extends Controller {
    public function handle(){
        //get user info
        $uid = (int)$this->request->query('uid');
        if(!$uid) return $this->respondWithData(10001);
        $user = UserDao::where('id',$uid)->where('status',1)->first(['nickname','headimgurl','sign']);
        if(!$user )return $this->respondWithData(20003);
        
        //get count
        $count = IssueDao::where('uid',$uid)->count();
        return $this->respondWithData(200,['user'=>$user,'count'=>$count]);
    }
}