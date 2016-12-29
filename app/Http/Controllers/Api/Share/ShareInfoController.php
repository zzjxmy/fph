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
use App\Model\Dao\UserDao;

class ShareInfoController extends Controller {
    public function handle(){
        if(!is_login()) return $this->respondWithData(10000);
        //get user info
        $user = UserDao::where('id',decrypt($this->request->input('uid')))->where('status',1)->first();
        if(!$user) return $this->respondWithData(20003);
        //get company is auth
        //
    }
}