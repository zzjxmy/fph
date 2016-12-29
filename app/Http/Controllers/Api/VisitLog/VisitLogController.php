<?php
/**
 * CopyRight © 2016
 * @desc 这里是控制器描述
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     VisitLog.php（类名/函数名）
 * @date:     2016-12-26
 * @directory: /app/classes/Controller/fph/VisitLog.php
 */

namespace App\Http\Controllers\Api\VisitLog;


use App\Http\Controllers\Controller;
use App\Model\Dao\UserDao;
use App\Model\Dao\VisitLogDao;
use Illuminate\Http\Request;

class VisitLogController extends Controller {
    
    public $visitLogDao;
    
    public function __construct ( Request $request ,VisitLogDao $visitLogDao) {
        $this->visitLogDao = $visitLogDao;
        parent::__construct($request);
    }
    
    public function handle(){
        //decrypt UID
        if(!is_login())return $this->respondWithData(10000);
        //get visit log where today
        $list = $this->visitLogDao->where('id',decrypt($this->request->input('uid')))
            ->where('add_time','>=',strtotime(date('Y-m-d')))
            ->with(['user' => function($query) {
                $query->select(['id','nickname','headimgurl']);
            }])->with(['issue' => function($query){
                $query->select(['id','title']);
            }])->paginate(15)->toArray();
        $list = $list['data'];
        //get user is band mobile
        $user = UserDao::where('id',decrypt($this->request->input('uid')))->first(['mobile']);
        return $this->respondWithData(200,['list' => $list , 'isBandMobile' => $user->mobile?1:0]);
    }
}