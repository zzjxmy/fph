<?php
/**
 * CopyRight © 2016
 * @desc 这里是控制器描述
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     OtherUserInfoController.php（类名/函数名）
 * @date:     2016-12-29
 * @directory: /app/classes/Controller/fph/OtherUserInfoController.php
 */

namespace App\Http\Controllers\Api\User;


use App\Http\Controllers\Controller;
use App\Model\Dao\CompanyDao;
use App\Model\Dao\IssueDao;
use App\Model\Dao\UserDao;

class OtherUserInfoController extends Controller {
    public function handle(){
        //decrypt UID
        if(!is_login())return $this->respondWithData(20003);
        //get other user info
        $uid = (int)$this->request->input('other');
        if(!$uid) return $this->respondWithData(10002);
        //get info
        $userInfo = UserDao::where('id',$uid)->where('status',1)->with(['city'=>function($query){
            $query->select('id','name');
        }])->first(['id','nickname','headimgurl','sex','city_id','sign','company_id']);
        if(!$userInfo)return $this->respondWithData(20003);
        //get publish list
        $publishList = IssueDao::where('uid',$uid)->with(['city' => function($query){
            $query->select('id','name');
        }])->with(['issueInfo' => function($query){
            $query->select('iid','role','agency_type');
        }])->with(['img'=>function($query){
            $query->select('iid','url')->where('type',2);
        }])->get(['id','city_id','title','total_price','com','add_time'])->toArray();
        foreach ($publishList as $key => $value){
            $publishList[$key]['issue_info']['role'] = config('message.role')[$value['issue_info']['role']];
            $publishList[$key]['issue_info']['agency_type'] = config('message.agency_type')[$value['issue_info']['agency_type']];
            $publishList[$key]['add_time'] = formatTime($value['add_time']);
        }
        //get company info
        $companyInfo = [ 'name' => ''];
        if($userInfo->company_id){
            $companyInfo = CompanyDao::where('id',$userInfo->company_id)->first(['id','name'])->toArray();
        }
        return $this->respondWithData(200,[
            'userInfo' => $userInfo ,
            'publishList' => $publishList ,
            'companyInfo' => $companyInfo
        ]);
    }
}
