<?php
/**
 * CopyRight © 2017
 * @desc 用户提交发布认证信息(未认证、认证失败时提交)
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     PublishSubCheckController.php（类名/函数名）
 * @date:     2017-01-03
 * @directory: /app/classes/Controller/fph/PublishSubCheckController.php
 */

namespace App\Http\Controllers\Api\Publish;


use App\Http\Controllers\Controller;
use App\Model\Dao\ImagesDao;
use App\Model\Dao\IssueDao;
use App\Model\Dao\IssueInfoDao;
use Illuminate\Support\Facades\DB;

class PublishSubCheckController extends Controller {
    public function handle()
    {
        if ( !is_login() ) return $this->respondWithData(20003);
    
        //get decrypt uid
        $uid = decrypt($this->request->input('uid'));
        //get publishId role images
        $publishId = $this->request->input('publishId');
        $role = $this->request->input('role');
        $images = (array)$this->request->input('images');
        //check
        if(!array_key_exists($role,config('message.role')) || !count($images)) return $this->respondWithData(10002);
        
        //get info by publish where rz_status in 1,4
        $publishInfo = IssueDao::where('id',$publishId)
            ->where('uid',$uid)
            ->whereIn('rz_status',[1,4])
            ->first();
        if(!$publishInfo) return $this->respondWithData(20014);
        
        //get old images
        $publishImages = ImagesDao::where('type',3)->where('iid',$publishId)->get(['id','url'])->toArray();
        $publishImages = array_column($publishImages,'url','id');
        $addImages = array_diff($images,$publishImages);
        $delImages = array_diff($publishImages,$images);
        $array = [];
        array_walk($addImages,function($item, $key) use (&$array,$publishId){
            $array[$key]['iid'] = $publishId;
            $array[$key]['type'] = 3;
            $array[$key]['url'] = $item;
        });
        //get issueInfo
        $issueInfo = IssueInfoDao::firstOrNew(['iid' => $publishId]);
        try{
            DB::transaction(function() use ($issueInfo,$role,$array,$delImages,$publishId){
                if($role != $issueInfo->role){
                    $issueInfo->iid = $publishId;
                    $issueInfo->role = $role;
                    $issueInfo->save();
                }
                if(count($delImages))ImagesDao::whereIn('id',array_keys($delImages))->delete();
                ImagesDao::insert($array);
            });
            return $this->respondWithData(200);
        }catch (\Exception $exception){
            return $this->respondWithData(20015);
        }
    }
}