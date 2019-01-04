<?php
namespace OTC\Validate;

use OTC\Config;

class OrderValidate extends Validate
{
    public function varietyValidate($var)
    {
        if (!array_key_exists($var, Config::getVarietyList())){
            return false;
        }
        
    }

    public function currencyValidate($var)
    {
        if (!array_key_exists($var, Config::getCurrencyList())){
            return false;
        }
        
    }

    public function totalAmountValidate($var)
    {
        if (!(is_numeric($var) && $var > 0 && $var < 5000)){
            return false;
        }
        
    }

    public function paymentTypeValidate($var)
    {
        if (!array_key_exists($var, Config::getPaymentType('v1'))){
            return false;
        }
        
    }

    public function paymentTypeV2Validate($var)
    {
        $paymentTypes = explode(',', $var);
        foreach ($paymentTypes as $paymentType){
            if (!array_key_exists($paymentType, Config::getPaymentType('v2'))){
                return false;
            }
        }
        
    }

    public function outOrderNoValidate($var)
    {
        if (!trim($var)){
            return false;
        }
        
    }

    public function outOrderIdsValidate($var)
    {
        if (!(($outOrderIds = explode(',', $var)) && array_filter($outOrderIds) == $outOrderIds)){
            return false;
        }
        
    }

    public function nameValidate($var)
    {
        
    }

    public function idNumberValidate($var)
    {
        
    }

    public function payOptionNumberValidate($var)
    {
        if (!trim($var)){
            return false;
        }
        
    }

    public function payOptionNameValidate($var)
    {
        if (!trim($var)){
            return false;
        }
        
    }

    public function payOptionBankValidate($var)
    {
        if (!trim($var)){
            return false;
        }
        
    }

    public function payOptionBankNameValidate($var)
    {
        if (!trim($var)){
            return false;
        }
        
    }

    public function pageNumValidate($var)
    {
        if (!(is_int($var) && $var > 0)){
            return false;
        }
        
    }

    public function pageSizeNumberValidate($var)
    {
        if (!(is_int($var) && $var > 0 && $var < 2001)){
            return false;
        }
        
    }

    public function fromTypeValidate($var)
    {
        if (!array_key_exists($var, Config::getFormType())){
            return false;
        }
        
    }

    public function contentValidate($var)
    {
        if (!trim($var)){
            return false;
        }
        
    }

    public function attachValidate($var)
    {
        if (!trim($var)){
            return false;
        }
        
    }

    public function dealTypeValidate($var)
    {
        if (!array_key_exists($var, Config::getDealType())){
            return false;
        }
        
    }

    public function remarkValidate($var)
    {
        if (!trim($var)){
            return false;
        }
        
    }
}
