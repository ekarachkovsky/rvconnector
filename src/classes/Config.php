<?php
namespace classes;


class Config
{
    private static $instance;
    private $config;
    private $configCache;

    private function __construct() {
        $this->config = include(realpath(dirname(__FILE__).'/../config.inc'));
        $this->configCache = array();
    }
    protected function __clone()
    {
    }

    private static function getInstance(){
        if(is_null(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function get($key)
    {
        $inst = self::getInstance();
        if(key_exists($key,$inst->configCache)){
            return $inst->configCache[$key];
        }
        $keyPath = explode('.',$key);
        $res = $inst->config;
        foreach ($keyPath as $item) {
            if(is_array($res) && key_exists($item,$res)) {
                $res = $res[$item];
            }else{
                throw new \Exception("Configuration file error: Key '$key' not found");
            }
        }
        $inst->configCache[$key]=$res;
        return $res;
    }

    public static function getRecordUrl($recordId){
        return "http://".self::get('recordsServer')."/records?id=$recordId";
    }
}