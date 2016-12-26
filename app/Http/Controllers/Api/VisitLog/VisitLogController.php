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
        if(!is_login())return $this->respondWithData(20003);
        //get visit log
        $list = $this->visitLogDao->paginate(15)->toArray();
        $list = $list['data'];
        foreach ($list as $key => $value){
            $list[$key]['add_time'] = date('Y-m-d H:i:s',$value['add_time']);
            $list[$key]['title'] = config('app.messageType')[$value['type']];
        }
        return $this->respondWithData(200,$list);
    }
}