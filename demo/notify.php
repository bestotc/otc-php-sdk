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

/************不同回调放到不同的action里*******************/
try{
    $im = new IM($conf);
    $im->callback(function ($data){
        //$data为业务数据的array格式
        //{
        //    "orderNo": "201810291009150NOLpBHPBkwQqhfX16",
        //    "messageType": "0",
        //    "fromType": 2,
        //    "content": "已确认放行",
        //    "attach": "http://www.sample.com/image/2019",
        //    "timestamp": 1541067368
        //}

        //业务处理
        var_dump($data);
    });


    $order = new Order($conf);
    $order->callback(function ($data){
        //$data为业务数据的array格式
        //{
        //	"amount": "0.85714285",
        //	"isReopen": false,
        //	"outOrderNo": "1546395729420",
        //	"price": "7.0000",
        //	"status": 3,
        //	"timestamp": "2019-01-02T02:22:53.509Z",
        //	"total": "6.0000",
        //	"type": "OrderStatusChangeMessage"
        //}
        //业务处理
        var_dump($data);
    });

}catch (Exception $e){
    echo($e->getMessage());
}
