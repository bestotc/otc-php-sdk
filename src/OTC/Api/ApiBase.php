<?php
namespace OTC\Api;

use OTC\Config;
use OTC\Auth;
use OTC\Exception\HttpResponseException;
use OTC\Exception\HttpResponseBodyException;
use OTC\Exception\InvalidArgumentException;
use OTC\Log\LoggerManager;
use OTC\Http\Request;
use OTC\Http\Client;

abstract class ApiBase
{

    protected $request;
    protected $response;
    protected $conf;
    private $auth;
    protected $apiConfigs = [];
    protected $loggerManager;

    // 构造函数
    public function __construct($conf = [])
    {
        $this->conf = new Config($conf);
        $this->loggerManager = new LoggerManager($this->conf->getLogPath(), $this->conf->getLogLevel());
    }

    public function getFullUrl($uri)
    {
        return $this->conf->getHost() . $uri;
    }

    public function getAuth()
    {
        if (empty($this->auth)){
            $this->auth = new Auth($this->conf);
        }

        return $this->auth;
    }

    protected function request($apiName, array $paramsVal, array $nonValidateCheckParams = [], array $paramsAliasName = [])
    {
        $ret = '';
        $requestString = '';
        $logger = $this->loggerManager->getLogger('request');
        $params = $this->_genParamsMapping($this, $apiName, $paramsVal);

        try{
            $this->_checkValidate($params, $nonValidateCheckParams, $paramsAliasName);
            $this->_genRequestInstance($apiName, $params);
            $requestString = $this->request->toString();
            $logger->info($requestString);

            $this->response = (new Client())->sendRequest($this->request);
            $responseToString = $this->response->toString();

            if ($this->response->ok()){
                $ret = $this->response->json('body');
                if (isset($ret['code']) && $ret['code'] != 200){
                    throw new HttpResponseBodyException($ret['msg']);
                }

                $logger->info($responseToString);
            }else{
                throw new HttpResponseException($responseToString);
            }
        }catch (\Exception $e){
            $logger->error($e->getMessage() . ' ' . $requestString);

        }

        $this->loggerManager->flush();
        if (isset($e)){
            throw $e;
        }

        return $ret;
    }

    private function _getCurrentValidate()
    {
        $calledClassNameSpilt = explode('\\', get_called_class());
        $calledClassName = array_pop($calledClassNameSpilt);
        $classValidate = '\OTC\Validate\\' .$calledClassName. 'Validate';

        if (class_exists($classValidate)){
            return $classValidate;
        }
    }

    private function _genParamsMapping($obj, $method, array $paramsVar)
    {
        $params = [];

        try{
            $reflectionMethod = new \ReflectionMethod($obj , $method);
            $paramNames = $reflectionMethod->getParameters();
            foreach ($paramNames as $k => $paramName){
                $params[$paramName->name] = (isset($paramsVar[$k]) ? trim($paramsVar[$k]) : '');
            }

        }catch (\ReflectionException $e){
            exit('ReflectionMethod Error');
        }

        return $params;
    }

    private function _checkValidate(array $params, array $NonValidateCheckParams, array $paramsAliasName)
    {
        if ($classValidate = $this->_getCurrentValidate()){
            (new $classValidate($params))
                ->cancelCheck($NonValidateCheckParams)
                ->setParamsAliasName($paramsAliasName)
                ->checkAll();
        }
    }

    private function _genRequestInstance($apiName, $params)
    {
        $apiConfig = $this->apiConfigs[$apiName];
        $url = $this->getFullUrl($apiConfig['uri']);
        $headers = ['Content-Type' => 'application/json;charset=\'utf-8\''];
        $requestMethod = isset($apiConfig['method']) ? $apiConfig['method'] : 'POST';
        $isSign        = isset($apiConfig['isSign']) ? $apiConfig['isSign'] : true;
        $this->request = new Request($requestMethod, $url, $headers, $params);
        if ($isSign){
            $this->getAuth()->signRequest($this->request);
        }
    }

    protected function _statusTransfer($status)
    {
        if ($status == 'success'){
            return true;
        }

        return false;
    }

}
