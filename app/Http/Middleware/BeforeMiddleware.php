<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Http\Response;

class BeforeMiddleware implements Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $decryptParams = $this->decrypt($request);
        if (is_int($decryptParams))
        {
            return $this->responseCode($decryptParams);
        }
        $request = $decryptParams;
        return $next($request);
    }

    private function decrypt($request)
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST')
        {
            return $request->all();
        }else{
            $params = $request->all();
            if(!$this->verifyParams($params)){
                return 10000;
            }
        }
        /*$defaultParam = config('app.defaultParams') ? config('app.defaultParams') : 'p';

        if (isset($request[$defaultParam]) && $request[$defaultParam])
        {
            //$request[$defaultParam] = "0CbPskgHpdgVKKc9WuKzT/6n3vsCxfvy9p68NWq+U1fmsiUfFMXD1UXmOxs3MWUYD6g9to4sfZs8iwva3+hQI77yYREo/84+15Ga+75McRqCJzvMyDXQYPMAFOQECvWnPX5bffG78IfN2lQoceYPUoquuMRtpa6IZBVIud1r/Iw=";
            $pValues = explode(',',$request[$defaultParam]);
            $decryptRes = "";
            foreach($pValues as $val){
                $decryptRes = $decryptRes.(Rsa::getInstance()->privateDecrypt($val));
            }
            $decryptRes = json_decode($decryptRes,true);
            return $decryptRes;
        } else {
            return 500;
        }*/
    }

    /**
     * @param $errorCode
     * @return $this
     */
    private function responseCode($code)
    {
        $errorCode = config('code.'.$code);
        return (new Response(json_encode([
            'code'     =>  $errorCode['code'],
            'msg'      =>  $errorCode['msg'],
            'data'     =>  ""
        ]), 200))->header("Content-Type", 'application/json');
    }

    /**
     * @param $params
     * @return bool
     */
    private function verifyParams($params)
    {
        $token = (isset($params['token'])) ? $params['token'] : '';
        $tokenKey = config('app.tokenKey');
        //echo md5($tokenKey . date('Ymd'));exit;
        if (!$token || ($token != md5($tokenKey . date('Ymd'))))
        {
            return false;
        }
        return true;
    }
}
