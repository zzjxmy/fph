<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Http\Response;

$method = $_SERVER['REQUEST_METHOD'];
if($method == "POST"){
    $app->$method('/{version}/{equipment}/{category}/{action}', function ($version, $equipment, $category, $action) use ($app) {
        //终端和版本检测
        $equipment_status = in_array(strtolower($equipment), config('app.versionConf')['client']);
        $version_status = false;
        if(isset(config('app.versionConf')[strtolower($equipment)])){
            $version_status   = in_array(strtolower($version), config('app.versionConf')[strtolower($equipment)]['version']);
        }
        if(!$version_status)   $errorCode = config('code.10001');
        if(!$equipment_status) $errorCode = config('code.10000');;
        if(isset($errorCode)){
            return (new Response(json_encode([
                'code'    =>  $errorCode['code'],
                'msg'     =>  $errorCode['msg'],
                'data'    =>  ""
            ]), 200))->header("Content-Type", 'application/json');
        }
        $category = ucfirst($category);
        $action = ucfirst($action);
        $controllerName = $action . "Controller";
        $controller = 'App\\Http\\Controllers\\Api\\' . $category . "\\" . $controllerName;
        if (!empty(class_exists($controller)))
        {
            $controllerMake = $app->make($controller);
            return $controllerMake->handle();
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }
    });
}else{
    $app->$method('/{category}/{action}/{function}', function ( $category, $action, $function) use ($app) {
        $category = ucfirst($category);
        $action = ucfirst($action);
        $function = ucfirst($function);
        $controllerName = $action . "Controller";
        $controller = 'App\\Http\\Controllers\\Web\\' . $category . "\\" . $controllerName;
        if (!empty(class_exists($controller)))
        {
            $controllerMake = $app->make($controller);
            return $controllerMake->$function();
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }
    });
}



