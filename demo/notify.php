<?php
/**
 * Created by PhpStorm.
 * User: mi
 * Date: 2018/12/29
 * Time: 下午12:16
 */
require_once('autoload.php');

use OTC\Notify\IM;
use OTC\Notify\Order;

$conf = [
    'AccessKeyId' => '',
    'AccessKeySecret' => '',
    'SignatureVersion' => 1,
    'SignatureMethod' => 'HmacSHA256',
    'Env' => 0, //0测试 1生产
    'LogLevel' => 1,
    'LogPath' => '',
];

try{
    $im = new IM($conf);
    $im->callback(function ($data){
        //业务处理
        var_dump($data);
    });

    $order = new Order($conf);
    $order->callback(function ($data){
        //业务处理
        var_dump($data);
    });

}catch (Exception $e){
    echo($e->getMessage());
}
