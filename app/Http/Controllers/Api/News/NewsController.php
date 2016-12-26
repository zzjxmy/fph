<?php
/**
 * CopyRight © 2016
 * @desc 这里是控制器描述
 * @author   张志坚<137512638@qq.com>
 * @version  1.0(系统当前版本号)
 * @name:     NewController.php（类名/函数名）
 * @date:     2016-12-26
 * @directory: /app/classes/Controller/fph/NewController.php
 */

namespace App\Http\Controllers\Api\News;


use App\Http\Controllers\Controller;
use App\Model\Dao\NewsDao;
use Illuminate\Http\Request;

class NewsController extends Controller {
    
    public $newDao;
    public function __construct ( Request $request ,NewsDao $newsDao)
    {
        $this->newDao = $newsDao;
        parent::__construct($request);
    }
    
    public function handle(){
        switch ($this->request->input('type')){
            case 'list':
                return $this->lists();
                break;
            case 'details':
                return $this->details();
                break;
            default:
                return $this->respondWithData(10002);
        }
    }
    
    public function details(){
        //get ID
        $id = (int)$this->request->input('id');
        if(empty($id) || $id == 0) return $this->respondWithData(10002);
        //get new
        $new = $this->newDao->where('id',$id)->where('status',1)->first();
        if($new){
            //pv +1
            $new->increment('pv',1);
            return $this->respondWithData(200,$new->toArray());
        }
        return $this->respondWithData(10000);
    }
    
    /**
     * get new list
     * limit 15
     * @return string
     */
    public function lists(){
        $list = $this->newDao->where('status',1)->paginate(15)->toArray();
        $data = $list['data'];
        foreach ($data as $key => $value){
            $data[$key]['add_time'] = formatTime($value['add_time']);
            unset($data[$key]['content'],$data[$key]['pv'],$data[$key]['status']);
        }
        return $this->respondWithData(200,$data);
    }
}