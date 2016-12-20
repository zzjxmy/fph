<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 15/11/25
 * Time: 下午1:55
 */

namespace App\Http\Controllers\Web\City;


use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function Index()
    {
        return view('city.test.index',['token' => $this->token()]);
    }

}