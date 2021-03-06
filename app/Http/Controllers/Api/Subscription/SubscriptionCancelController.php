<?php
/**
 * CopyRight © 2017
 * @desc 取消订阅功能
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     SubscriptionCancelController.php（类名/函数名）
 * @date:     2017-01-05
 * @directory: /app/classes/Controller/fph/SubscriptionCancelController.php
 */


namespace App\Http\Controllers\Api\Subscription;


use App\Http\Controllers\Controller;
use App\Model\Dao\SubsidyDao;

class SubscriptionCancelController extends Controller {
    public function handle(){
        //decrypt UID
        if(!is_login())return $this->respondWithData(20003);
        $uid = decrypt($this->request->input('uid'));
        $type = $this->request->input('type');
        if(!array_key_exists($type,config('message.class')))return $this->respondWithData(10002);
        //get info
        $info = SubsidyDao::where('type',$type)->where('uid',$uid)->first();
        if(!$info) return $this->respondWithData(10000);
        
        if(!SubsidyDao::where('type',$type)->where('uid',$uid)->delete()) return $this->respondWithData(20016);
        return $this->respondWithData(200);
    }
}