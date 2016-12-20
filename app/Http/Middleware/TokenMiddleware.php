<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 15/10/29
 * Time: 下午1:44
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

class TokenMiddleware
{
    public function handle($request, Closure $next)
    {
        if (env('APP_ENV') == 'local')
        {
            $params = $request->all();
            $token = (isset($params['t'])) ? $params['t'] : '';
            $tokenKey = config('app.tokenKey');
            if (!$token || ($token != md5($tokenKey . date('Ymd'))))
            {
                $error = config('code.10000');
                return (new Response(json_encode([
                    'code'     =>  $error['code'],
                    'msg'      =>  $error['msg'],
                    'data'     =>  ""
                ]), 200))->header("Content-Type", 'application/json');
            }
        }
        return $next($request);
    }
}