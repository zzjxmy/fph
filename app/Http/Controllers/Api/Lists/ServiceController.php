<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 15/11/25
 * Time: 下午1:55
 */

namespace App\Http\Controllers\Api\Lists;


use App\Http\Controllers\Controller;
use App\Model\Bll\Issue\IssueBll;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public $request;

    public function __construct(Request $request,IssueBll $issueBll)
    {
        $this->request = $request;
        parent::__construct($this->request);
        $this->issueBll = $issueBll;
    }

    public function handle()
    {
        $type = (isset($this->params['type']) && $this->params['type']) ? $this->params['type']:0;
        $search = (isset($this->params['search']) && $this->params['search']) ? $this->params['search']:'';
        $data = $this->issueBll->getServiceLists($type ,$search);
        return $this->respondWithData(200, $data);
    }
}