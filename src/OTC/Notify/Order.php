<?php
namespace OTC\Notify;

use OTC\Aws\Sns\Message;
use OTC\Aws\Sns\MessageValidator;
use OTC\Config;
use OTC\Exception\HttpResponseException;
use OTC\Log\LoggerManager;

final class Order
{
    private $loggerManager;
    private $conf;
    private $data;

    public function __construct(array $conf = [])
    {
        $this->conf = new Config($conf);
        $this->loggerManager = new LoggerManager($this->conf->getLogPath(), $this->conf->getLogLevel());
        $this->_validateData();
    }

    private function _validateData()
    {
        $logger = $this->loggerManager->getLogger('orderNotify');

        try{
            $message = Message::fromRawPostData();
            $postData =  $message->toArray();
            $postDataJson = 'post data:' . json_encode($postData);
            $messageValidator = new MessageValidator();
            $isValid = $messageValidator->isValid($message);
            $logger->info($postDataJson);

            if ($isValid){
                $this->data = $postData;
                if ($postData['Type'] == 'SubscriptionConfirmation'){
                    $this->_subscribeRequest($postData['SubscribeURL']);
                }
            }else{
                throw new HttpResponseException('数据验证错误');
            }
        }catch (\Exception $e){
            $eMessage = $e->getMessage();
            if (isset($postDataJson)){
                $eMessage .= ' ' . $postDataJson;
            }else{
                $eMessage .= ' post data:' . json_encode(file_get_contents('php://input'));
            }

            $logger->error($eMessage);
        }

        $this->loggerManager->flush();

        if (isset($e)){
            throw $e;
        }

    }

    private function _callback(callable $eventCallback, $notifyType)
    {
        if ($this->data['Type'] == $notifyType){
            call_user_func($eventCallback, $this->data['message']);
        }
    }

    public function callback($eventCallback)
    {
        $this->_callback($eventCallback, 'Notification');
    }

    private function _subscribeRequest($subscribeURL)
    {
        $logger = $this->loggerManager->getLogger('SubscriptionConfirmation');
        $logger->info('SubscribeURL:' . $subscribeURL);

        $ret = file_get_contents($subscribeURL);

        $logger->info($ret);
        $this->loggerManager->flush();


    }
}
