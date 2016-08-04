<?php
namespace classes;
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

class Log
{
    private static $instance;
    private $logs;
    private $loglevel;

    private function __construct() {
        $this->logs = array();
        $levels = Logger::getLevels();

        $this->loglevel = $levels[Config::get("log.level")];
    }

    private static function getInstance(){
        if(self::$instance==null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private static function getLog($name){
        $instance = self::getInstance();
        if(!key_exists($name,$instance->logs)){
            $instance->logs[$name] = new Logger($name);
            $dest = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, Config::get('log.destination'));
            if(empty($dest)){
                die("Log file is not set! Check config.inc for key log->destination");
            }

            if($dest[0]!='/' && substr($dest, 1, 2) != ':'){
                // if local directory
                $dest = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR."$dest";
            }
            if(!file_exists(dirname($dest))){
                mkdir(dirname($dest),0777,true);
            }

            if(!touch($dest) || !is_writable($dest)){
                die("Log file $dest is not accessible!");
            }

            $instance->logs[$name]->pushHandler(new StreamHandler($dest, $instance->loglevel));
        }
        return $instance->logs[$name];
    }

    public static function Debug($name,$message=false,$file=false,$line=false){
        $log = self::getLog($name);
        if($file && $line){
            $message = "<$file::$line>: $message";
        }
        $log->debug($message);
    }
    public static function Info($name,$message=false,$file=false,$line=false){
        $log = self::getLog($name);
        if($file && $line){
            $message = "<$file::$line>: $message";
        }
        $log->info($message);
    }
    public static function Notice($name,$message=false,$file=false,$line=false){
        $log = self::getLog($name);
        if($file && $line){
            $message = "<$file::$line>: $message";
        }
        $log->notice($message);
    }
    public static function Warning($name,$message=false,$file=false,$line=false){
        $log = self::getLog($name);
        if($file && $line){
            $message = "<$file::$line>: $message";
        }
        $log->warning($message);
    }
    public static function Error($name,$message=false,$file=false,$line=false){
        $log = self::getLog($name);
        if($file && $line){
            $message = "<$file::$line>: $message";
        }
        $log->error($message);
    }
    public static function Critical($name,$message=false,$file=false,$line=false){
        $log = self::getLog($name);
        if($file && $line){
            $message = "<$file::$line>: $message";
        }
        $log->critical($message);
    }
    public static function Alert($name,$message=false,$file=false,$line=false){
        $log = self::getLog($name);
        if($file && $line){
            $message = "<$file::$line>: $message";
        }
        $log->alert($message);
    }
    public static function Emergency($name,$message=false,$file=false,$line=false){
        $log = self::getLog($name);
        if($file && $line){
            $message = "<$file::$line>: $message";
        }
        $log->emergency($message);
    }
}