<?php

return array(
    
    //公众平台
    'appId' => 'wx3ce1eceec205c6c4',
    'appSecret' => '6a377f78c74e13ff5e4e425af7b11ecc',
    'token' => '',
    'encodingAesKey' => '',
    'middleUrl' => null,
    
    //微信支付
    'mchId' => 'xxxxxxx',
    'apiSecret' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxx',
    'sslCert' => '/Users/ethan/Desktop/esitools/cert/apiclient_cert.pem',
    'sslKey' => '/Users/ethan/Desktop/esitools/cert/apiclient_key.pem',
    'caInfo' => '/Users/ethan/Desktop/esitools/cert/rootca.pem',
    
    //日志
    'log' => array(
        'class' => 'PFinal\Wechat\Support\Logger',
        'name' => 'pfinal.wechat',
        'level' => Monolog\Logger::DEBUG,
        'file' => './wechat.log',
    ),
    
    //会话
    'session' => array(
        'class' => 'PFinal\Session\NativeSession',
        'keyPrefix' => 'pfinal.wechat'
    ),
    
    //缓存
    'cache' => array(
        'class' => 'PFinal\Cache\FileCache',
        'keyPrefix' => 'pfinal.wechat'
    ),
);