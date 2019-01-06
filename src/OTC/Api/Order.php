<?php
namespace OTC\Api;

use OTC\Api\ApiBase;
use OTC\Exception\InvalidArgumentException;
use OTC\Exception\HttpResponseException;
use OTC\Exception\HttpResponseBodyException;

class Order extends ApiBase
{
    protected $apiConfigs = [
        'buyV1' => [
            'uri' => '/v1/api/openotc/order/buy',
            //'method' => 'POST', 默认
            //'isSign' => true, 默认
        ],
        'buyV2' => [
            'uri' => '/v2/api/openotc/order/buy',
            
        ],
        'confirmPay' => [
            'uri' => '/v1/api/openotc/order/buy/confirmPay',
            
        ],
        'cancel' => [
            'uri' => '/v1/api/openotc/order/cancel',
            
        ],
        'sell' => [
            'uri' => '/v1/api/openotc/order/sell',
            
        ],
        'confirmReceive' => [
            'uri' => '/v1/api/openotc/order/confirmReceive',
            
        ],
        'lists' => [
            'uri' => '/v1/api/openotc/order/list',
            
        ],
        'listsBatch' => [
            'uri' => '/v1/api/openotc/order/list/batch',
            
        ],
        'detail' => [
            'uri' => '/v1/api/openotc/order/detail',
            
        ],
        'price' => [
            'uri' => '/v1/api/openotc/price',
            
        ],
        'appealSubmit' => [
            'uri' => '/v1/api/openotc/order/appeal/submit',
            
        ],
        'appealMessageSend' => [
            'uri' => '/v1/api/openotc/order/appeal/message/send',
            
        ],
        'appealDetail' => [
            'uri' => '/v1/api/openotc/order/appeal/detail',
            
        ],
        'appealMessageList' => [
            'uri' => '/v1/api/openotc/order/appeal/message/list',
            
        ],
        'appealDeal' => [
            'uri' => '/v1/api/openotc/order/appealDeal',
            
        ],
    ];

    /**
     * 买币下单V1
     * @param $variety
     * @param $currency
     * @param $totalAmount
     * @param $paymentType
     * @param $outOrderNo
     * @param $name
     * @param $idNumber
     * @return array
     * @throws InvalidArgumentException|HttpResponseException|HttpResponseBodyException|\Exception
     */
    public function buyV1(
        $variety,
        $currency,
        $totalAmount,
        $paymentType,
        $outOrderNo,
        $name = '',
        $idNumber = ''
    ) {
        $nonValidateParams = ['name', 'idNumber'];
        $response = $this->request(__FUNCTION__ , func_get_args(), $nonValidateParams);

        return $response;
    }

    /**
     * 买币下单V2
     * @param $variety
     * @param $currency
     * @param $totalAmount
     * @param $paymentType
     * @param $outOrderNo
     * @param $name
     * @param $idNumber
     * @return array
     * @throws InvalidArgumentException|HttpResponseException|HttpResponseBodyException|\Exception
     */
    public function buyV2(
        $variety,
        $currency,
        $totalAmount,
        $paymentType,
        $outOrderNo,
        $name,
        $idNumber
    ) {
        $response = $this->request(__FUNCTION__ , func_get_args(), [], ['paymentType' => 'paymentTypeV2']);

        return $response;

    }

    /**
     * 确认付款
     * @param $outOrderNo
     * @param $name
     * @param $idNumber
     * @return bool
     * @throws InvalidArgumentException|HttpResponseException|\Exception
     */
    public function confirmPay($outOrderNo, $name, $idNumber)
    {
        $nonValidateParams = ['name', 'idNumber'];
        $response = $this->request(__FUNCTION__ , func_get_args(), $nonValidateParams);

        return $this->_statusTransfer($response['status']);

    }

    /**
     * 撤销订单
     * @param $outOrderNo
     * @return bool
     * @throws InvalidArgumentException|HttpResponseException|\Exception
     */
    public function cancel($outOrderNo)
    {
        $response = $this->request(__FUNCTION__ , func_get_args());

        return $this->_statusTransfer($response['status']);

    }

    /**
     * 卖币下单
     * @param $variety
     * @param $currency
     * @param $totalAmount
     * @param $amount
     * @param $payOptionNumber
     * @param $payOptionName
     * @param $payOptionBank
     * @param $payOptionBankName
     * @param $outOrderNo
     * @param string $name
     * @param string $idNumber
     * @return bool
     * @throws InvalidArgumentException|HttpResponseException|\Exception
     */
    public function sell
    (
        $variety,
        $currency,
        $totalAmount,
        $amount,
        $payOptionNumber,
        $payOptionName,
        $payOptionBank,
        $payOptionBankName,
        $outOrderNo,
        $name = '',
        $idNumber = ''
    ) {
        if ($totalAmount && $amount){
            throw new InvalidArgumentException('totalAmount 和 amount 只能填写一项');
        }

        $nonValidateParams = ['name', 'idNumber'];
        $response = $this->request(__FUNCTION__ ,func_get_args() , $nonValidateParams);

        return $this->_statusTransfer($response['status']);

    }

    /**
     * 确认收款
     * @param $outOrderNo
     * @return bool
     * @throws InvalidArgumentException|HttpResponseException|\Exception
     */
    public function confirmReceive($outOrderNo)
    {
        $response = $this->request(__FUNCTION__ , func_get_args());

        return $this->_statusTransfer($response['status']);

    }

    /**
     * 订单列表
     * @param $startTime
     * @param $endTime
     * @param int $pageNum
     * @param int $pageSize
     * @return array
     * @throws InvalidArgumentException|HttpResponseException|HttpResponseBodyException|\Exception
     */
    public function lists($startTime, $endTime, $pageNum = 1, $pageSize = 10)
    {
        $args = func_get_args();
        $args[2] = isset($args[2]) ? $args[2] : 10;
        $args[3] = isset($args[3]) ? $args[3] : 1;

        $response = $this->request(__FUNCTION__ , $args);

        return $response;
    }

    /**
     * 批量查询订单列表
     * @param $outOrderIds
     * @return array
     * @throws InvalidArgumentException|HttpResponseException|HttpResponseBodyException|\Exception
     */
    public function listsBatch($outOrderIds)
    {
        $response = $this->request(__FUNCTION__ , func_get_args());

        return $response;

    }

    /**
     * 订单详情
     * @param $outOrderNo
     * @return array
     * @throws InvalidArgumentException|HttpResponseException|HttpResponseBodyException|\Exception
     */
    public function detail($outOrderNo)
    {
        $response = $this->request(__FUNCTION__ , func_get_args());

        return $response;

    }

    /**
     * @param $variety
     * @param $currency
     * @return array
     * @throws InvalidArgumentException|HttpResponseException|HttpResponseBodyException|\Exception
     */
    public function price($variety, $currency)
    {
        $response = $this->request(__FUNCTION__ , func_get_args());

        return $response;

    }

    /**
     * 发起申诉
     * @param $outOrderNo
     * @return bool
     * @throws InvalidArgumentException|HttpResponseException|\Exception
     */
    public function appealSubmit($outOrderNo)
    {
        $response = $this->request(__FUNCTION__ , func_get_args());

        return $this->_statusTransfer($response['status']);
    }

    /**
     * 发送消息
     * @param $outOrderNo
     * @param $fromType
     * @param $content
     * @param $attach
     * @return bool
     * @throws InvalidArgumentException|HttpResponseException|\Exception
     */
    public function appealMessageSend($outOrderNo, $fromType, $content, $attach)
    {//$content, $attach 二选一
        $nonValidateParams = [];
        $args = func_get_args();

        if ($content && $attach){
            throw new InvalidArgumentException('content 和 attach 只能填写一项');
        }


        if (isset($args[2]) && $args[2]){
            $nonValidateParams[] = 'attach';
        }else{
            $nonValidateParams[] = 'content';
        }

        $response = $this->request(__FUNCTION__ , $args, $nonValidateParams);

        return $this->_statusTransfer($response['status']);

    }
    /**
     * 取申诉详情
     * @param $outOrderNo
     * @return array
     * @throws InvalidArgumentException|HttpResponseException|HttpResponseBodyException|\Exception
     */
    public function appealDetail($outOrderNo)
    {
        $response = $this->request(__FUNCTION__ , func_get_args());

        return $response;
    }

    /**
     * 获取申诉消息列表
     * @param $outOrderNo
     * @param $lastKey
     * @return array
     * @throws InvalidArgumentException|HttpResponseException|HttpResponseBodyException|\Exception
     */
    public function appealMessageList($outOrderNo, $lastKey = '')
    {
        $nonValidateParams[] = 'lastKey';
        $response = $this->request(__FUNCTION__ , func_get_args(), $nonValidateParams);

        return $response;
    }

    /**
     * 申诉订单处理
     * @param $outOrderNo
     * @param $dealType
     * @param $remark
     * @return bool
     * @throws InvalidArgumentException|HttpResponseException|\Exception
     */
    public function appealDeal($outOrderNo, $dealType, $remark)
    {
        $response = $this->request(__FUNCTION__ , func_get_args());

        return $this->_statusTransfer($response['status']);
    }
}
