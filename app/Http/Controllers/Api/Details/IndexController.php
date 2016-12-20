<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 15/11/25
 * Time: 下午1:55
 */

namespace App\Http\Controllers\Api\Details;


use App\Http\Controllers\Controller;
use App\Model\Bll\City\CityBll;
use App\Model\Bll\Issue\IssueBll;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public $request;

    public function __construct(Request $request,IssueBll $issueBll,CityBll $cityBll)
    {
        $this->request = $request;
        parent::__construct($this->request);
        $this->issueBll = $issueBll;
        $this->cityBll = $cityBll;
    }

    public function handle()
    {
        $v = $this->verifyParams($this->params, [
            'id' => 'required',
        ]);
        if($v) return $this->respondWithData(10002);
        $result = $this->issueBll->getDatails($this->params['id']);
        $data = $this->buildData($result);
        return $this->respondWithData(200, $data);
    }

    private function buildData($data){
        $data['addrs'] = $this->cityBll->getAddrsCity($data['addrs']);
        $data['add_time'] = formatTime($data['add_time']);
        return $data;
    }
}