<?php
/**
 * CopyRight © 2016
 * @desc 获取用户的发布列表
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     OtherUserInfoController.php（类名/函数名）
 * @date:     2016-12-29
 * @directory: /app/classes/Controller/fph/OtherUserInfoController.php
 */

namespace App\Http\Controllers\Api\Publish;


use App\Http\Controllers\Controller;
use App\Model\Dao\IssueDao;

class PublishListController extends Controller {
    public function handle(){
        //decrypt UID
        if(!is_login())return $this->respondWithData(20003);
        $uid = decrypt($this->request->input('uid'));
        $type = $this->request->input('type',1);
        if(!$type || !in_array($type,[1,2,3])) return $this->respondWithData(10002);
        //get publish list
        $publishList = IssueDao::where('uid',$uid)->where(function($query) use ($type){
            switch ($type){
                case 1:
                    $query->where('class',1);
                    break;
                case 2:
                    $query->whereIn('class',[2,3]);
                    break;
                case 3:
                    $query->whereIn('class',[4,5,6,7]);
                    break;
            }
        })->with(['city' => function($query){
            $query->select('id','name');
        }])->with(['issueInfo' => function($query){
            $query->select('iid','role','agency_type');
        }])->with(['img'=>function($query){
            $query->select('iid','url')->where('type',2);
        }])->get(['id','city_id','title','check_status','rz_status','total_price','com','add_time'])->toArray();
        foreach ($publishList as $key => $value){
            $publishList[$key]['issue_info']['role'] = config('message.role')[$value['issue_info']['role']];
            $publishList[$key]['issue_info']['agency_type'] = config('message.agency_type')[$value['issue_info']['agency_type']];
            $publishList[$key]['add_time'] = formatTime($value['add_time']);
        }
        return $this->respondWithData(200,$publishList);
    }
}
