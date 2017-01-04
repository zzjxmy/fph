<?php
/**
 * CopyRight © 2017
 * @desc 查看发布认证信息
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     PublishCheckInfoController.php（类名/函数名）
 * @date:     2017-01-03
 * @directory: /app/classes/Controller/fph/PublishCheckInfoController.php
 */

namespace App\Http\Controllers\Api\Publish;


use App\Http\Controllers\Controller;
use App\Model\Dao\ImagesDao;
use Illuminate\Support\Facades\DB;

class PublishCheckInfoController extends Controller
{
    public function handle ()
    {
        if ( !is_login() ) return $this->respondWithData(20003);
        
        //get decrypt uid
        $uid = decrypt($this->request->input('uid'));
        //get publishId
        $publishId = $this->request->input('publishId');
        //get info by publish where rz_status in 1,4
        $publishInfo = DB::table('issue')->leftjoin('issue_info','issue.id','=','issue_info.iid')
            ->where('issue.id',$publishId)
            ->where('issue.uid',$uid)
            ->whereIn('issue.rz_status',[1,4])
            ->first(['issue.title','issue_info.role']);
        if(!$publishInfo) return $this->respondWithData(20014);
        //get all role
        $roles = config('message.role');
        //get publish check info
        $publishCheckImages = ImagesDao::where('type',3)->where('iid',$publishId)->get(['url']);
        return $this->respondWithData(200,compact('publishInfo','roles','publishCheckImages'));
    }
}