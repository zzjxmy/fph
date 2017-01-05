<?php
/**
 * CopyRight © 2017
 * @desc 获取我的订阅列表
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     SubscriptionListController.php（类名/函数名）
 * @date:     2017-01-05
 * @directory: /app/classes/Controller/fph/SubscriptionListController.php
 */


namespace  App\Http\Controllers\Api\Subscription;


use App\Http\Controllers\Controller;
use App\Model\Dao\SubsidyDao;

class SubscriptionListController extends Controller {
    public function handle(){
        //decrypt UID
        if(!is_login())return $this->respondWithData(20003);
        //get list page
        $uid = decrypt($this->request->input('uid'));
        
        $list = SubsidyDao::where('uid',$uid)->get(['type'])->toArray();
        foreach ($list as $key => $value){
            $list[$key]['subject'] = config('message.class')[$value['type']];
            $list[$key]['count'] = SubsidyDao::countType($value['type']);
        }
        return $this->respondWithData(200,$list);
    }
}