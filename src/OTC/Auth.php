<?php
namespace OTC;

use OTC\Aws\Sns\Message;
use OTC\Aws\Sns\MessageValidator;
use OTC\Http\Request;

final class Auth
{
    private $conf;

    public function __construct(Config $conf)
    {
        $this->conf = $conf;
    }

    public function HmacSHA256($data, $secret = '')
    {
        if (!$secret){
            $secret = $this->conf->getAccessKeySecret();
        }

        $hmac = base64_encode(hash_hmac('sha256', $data, $secret, true));
        return $hmac;
    }

    public function signRequest(Request &$request)
    {
        $params = [
            'AccessKeyId' => $this->conf->getAccessKeyId(),
            'SignatureVersion' => $this->conf->getSignatureVersion(),
            'SignatureMethod' => $this->conf->getSignatureMethod(),
            'Timestamp' => gmdate('Y-m-d\TH:i:s'),
        ];

        $urlParsed = parse_url($request->url);

        if (array_key_exists('query', $urlParsed)) {
            $queryFields = explode('&' , $urlParsed['query']);
            foreach ($queryFields as $queryField){
                $queryFieldKV = explode('=', $queryField);
                $params[$queryFieldKV[0]] = $queryFieldKV[1];
            }
        }

        if (!empty($request->body)){
            $params = array_merge($params, $request->body);
        }

        ksort($params);
        $paramString = '';
        foreach ($params as $k => $param){
            $paramString .= $k . '=' . $this->percentEncode($param) . '&';
        }

        $signData = [
            strtoupper($request->method),
            strtolower($urlParsed['host']),
            $urlParsed['path'],
            substr($paramString , 0,-1)
        ];

        $params['Signature'] = $this->HmacSHA256(implode("\n", $signData));

        $request->body = $params;
    }

    public function isValidNotify(Message $message)
    {
        $messageValidator = new MessageValidator();
        $isValid = $messageValidator->isValid($message);

        if ($isValid){
            $messageData =  $message->toArray();
            if ($messageData['Type'] == 'Notification'
                && $messageData['Subject'] != $this->HmacSHA256($messageData['Message'], $this->conf->getNotifyKeySecret())){

                $isValid = false;
            }
        }

        return $isValid;
    }

    private function percentEncode($res)
    {
        $res = trim(utf8_encode(urlencode($res)));
        $res = str_replace(array('+','*','%7E'), array('%20','%2A','~'), $res);

        return $res;
    }

}
