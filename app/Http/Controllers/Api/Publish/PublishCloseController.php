<?php
/**
 * CopyRight © 2016
 * @desc 关闭用户发布的内容
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     PublishCloseController.php（类名/函数名）
 * @date:     2016-12-29
 * @directory: /app/classes/Controller/fph/PublishCloseController.php
 */

namespace App\Http\Controllers\Api\Publish;


use App\Http\Controllers\Controller;
use App\Model\Dao\IssueDao;

class PublishCloseController extends Controller {
    public function handle(){
        //decrypt UID
        if(!is_login())return $this->respondWithData(20003);
        //get other user info
        $uid = decrypt($this->request->input('uid'));
        $publishId = decrypt($this->request->input('publishId'));
        
        //change publish where check_status value 1 or 3
        $result = IssueDao::where('uid',$uid)->where('id',$publishId)->whereIn('check_status',[1,3])->update(['status',2]);
        if(!$result) return $this->respondWithData(20013);
        return $this->respondWithData(200);
    }
}