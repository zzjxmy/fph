<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 15/11/19
 * Time: 下午2:42
 */
return [
    'versionConf' => [
        'client'    => ['ios', 'android', 'web'],
        'ios'       => [
            'version' => [
                '1.0.0',
            ],
        ],
        'android'   => [
            'version' => [
                '1.0.0',
            ],
        ],
        'web'       => [
            'version' => [
                '1.0.0',
            ],
        ],
    ],
    'equipment' =>  [
        'Ios'       =>  1,
        'Android'   =>  1,
        'Web'        =>  1,
    ],
    'tokenKey'  =>  env('APP_KEY',''),
    // 图片上传配置
    'uploadConf' => [
        'module' => 'property',
        'type'   => 8,
        'url'   => env('OSS_UPLOAD_SERVER', 'http://ossapi.develop.corp.com/') . 'img/upload'
    ],
    //读取图片配置
    'getUploadPath' => [
        'module' => 'property',
        'imgMM'   => '420*600',
        'serverUrl'   => env('OSS_UPLOAD_SERVER', 'http://ossapi.develop.corp.com/') . 'img/getImgUrl'
    ],
    // 头像图片oss配置
    'avatar_uploadConf' => [
        'upload'  => [
            'module' => 'user',
            'type'   => 3,
            'url'   => env('OSS_UPLOAD_SERVER', 'http://ossapi.develop.corp.com/') . 'img/upload'
        ],
        'read'   =>  [
            'module' => 'user',
            'imgMM'   => '240*240',
            'serverUrl'   => env('OSS_UPLOAD_SERVER', 'http://ossapi.develop.corp.com/') . 'img/getImgUrl'
        ],
    ],
    // 头像图片oss配置
    'gift_uploadConf' => [
        'upload'  => [
            'module' => 'comment',
            'type'   => 1,
            'url'   => env('OSS_UPLOAD_SERVER', 'http://ossapi.develop.corp.com/') . 'img/upload'
        ],
        'read'   =>  [
            'module' => 'comment',
            'imgMM'   => '240*240',
            'serverUrl'   => env('OSS_UPLOAD_SERVER', 'http://ossapi.develop.corp.com/') . 'img/getImgUrl'
        ],
    ],
    'SmsUrl'    => 'http://fphapi.fangpinhui.com/',

];