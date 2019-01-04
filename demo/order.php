<?php
/**
 * Created by PhpStorm.
 * User: mi
 * Date: 2018/12/29
 * Time: 下午12:16
 */
require_once('../autoload.php');

use OTC\Api\Order;
//可以是用配置数组，也可以到src/Config.php中直接设置
$conf = [
    'AccessKeyId' => '',
    'AccessKeySecret' => '',
    'SignatureVersion' => 1,
    'SignatureMethod' => 'HmacSHA256',
    'Env' => 0, //0测试 1生产
    'LogLevel' => 1, //0关闭 1全部 2错误
    'LogPath' => '',
];

try{
    $order = new Order($conf);

    //获取币种单价
    $ret = $order->price('usdt', 'CNY');
    //买币下单V1
    $ret = $order->buyV1('usdt', 'CNY', 1, 3, 'test004');
    //买币下单V2
    $ret = $order->buyV2(
        'usdt',
        'CNY',
        1,
        3,
        'test004',
        '123' ,
        '809380459830948');
    //确认付款
    $ret = $order->confirmPay('test004','123' , '809380459830948');
    //撤销订单
    $ret = $order->cancel('test004');
    //卖币下单,totalAmount与amount二选一填写
    $ret = $order->sell(
        'usdt',
        'CNY',
        1,
        2,
        '6225880100000000',
        '张三',
        '招商银行',
        '亚运村支行',
        'test004');
    //确认收款
    $ret = $order->confirmReceive('test004');
    //订单列表
    //时间是UTC格式，可以用gmdate('Y-m-d\TH:i:s')获取
    $ret = $order->lists('2018-10-01T01:39:52', '2018-11-01T01:39:52', 1, 10);
    //批量查询订单列表
    $ret = $order->listsBatch('test004,test005');
    //订单详情
    $ret = $order->detail('test004');

    //发起申诉
    $ret = $order->appealSubmit('test004');
    //发送消息,content与attach二选一填写
    $ret = $order->appealMessageSend('test004', 1, '订单异常怎么处理？', '');
    $ret = $order->appealMessageSend('test004', 1, '', 'http://xxxxx.com/attach/image.jpg');
    //取申诉详情
    $ret = $order->appealDetail('test001');
    //获取申诉消息列表
    //lastKey 首次拉取不需要传，当消息不能一次取完时，接口返回 lastKey，再次取时将该值原样返回请求； 返回的 lastKey == null 表示已取完； 返回的消息列表取最新的消息，再按时间正排序；
    $ret = $order->appealMessageList('test001', 'lastKey');
    //申诉订单处理
    $ret = $order->appealDeal('test001', 1, '交易完成');

    var_dump($ret);

}catch (\OTC\Exception\InvalidArgumentException $e){//参数异常
    echo($e->getMessage());

}catch (\OTC\Exception\HttpResponseBodyException $e){//业务异常
    echo($e->getMessage());

}catch (\OTC\Exception\HttpResponseException $e){//通信异常
    echo($e->getMessage());

}catch (Exception $e){//其他
    echo($e->getMessage());
}
