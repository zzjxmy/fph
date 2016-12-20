<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 15/11/25
 * Time: 下午1:55
 */

namespace App\Http\Controllers\Api\City;


use App\Http\Controllers\Controller;
use App\Model\Bll\City\CityBll;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public $request;

    public function __construct(Request $request,CityBll $cityBll)
    {
        $this->request = $request;
        parent::__construct($this->request);
        $this->cityBll = $cityBll;
    }

    public function handle()
    {
        $data = $this->cityBll->getHotCity();
        return $this->respondWithData(200, $data);
    }
}