<?php
/**
 * CopyRight © 2016
 * @desc 用户系统消息
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     MessageController.php（类名/函数名）
 * @date:     2016-12-26
 * @directory: /app/classes/Controller/fph/MessageController.php
 */

namespace App\Http\Controllers\Api\Message;

use App\Http\Controllers\Controller;
use App\Model\Dao\MessageDao;
use Illuminate\Http\Request;

class MessageController extends Controller {
    public $messageDao;
    
    public function __construct ( Request $request ,MessageDao $messageDao) {
        $this->messageDao = $messageDao;
        parent::__construct($request);
    }
    
    public function handle(){
        //decrypt UID
        if(!is_login())return $this->respondWithData(10000);
        //get message list page
        $list = $this->messageDao->where('uid',decrypt($this->request->input('uid')))->paginate(15)->toArray();
        $list = $list['data'];
        foreach ($list as $key => $value){
            $list[$key]['add_time'] = date('Y-m-d H:i:s',$value['add_time']);
            $list[$key]['title'] = config('app.messageType')[$value['type']];
        }
        return $this->respondWithData(200,$list);
    }
}