<?php
namespace classes;
use \classes\Log;


class VTiger
{
    private $callbackUri;
    private $callbackSecret;

    function __construct()
    {
        $this->callbackUri = Config::get('Vtiger.VtigerURL');
        $this->callbackSecret = Config::get('Vtiger.VtigerSecretKey');
    }

    public function Send($command){
        $params = "vtigersignature=".$this->callbackSecret;
        foreach($command as $name=>$value){
            $params .= "&$name=$value";
        }
        try {
            Log::Debug(__CLASS__,"Trying send message to Vtiger: " . $this->callbackUri . "/modules/PBXManager/callbacks/PBXManager.php with parameters ".print_r($params,true));
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->callbackUri . "/modules/PBXManager/callbacks/PBXManager.php");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept-Charset: UTF-8',
                'Accept: application/json'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $res = curl_exec($ch);

            curl_close($ch);
        }catch(\Exception $ex){
            Log::Error(__CLASS__,"Message to Vtiger was not sent: ".print_r($ex,true),__FILE__,__LINE__);
        }
    }
}