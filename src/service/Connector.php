<?php
require realpath(dirname(__FILE__).'/../vendor/autoload.php');
//declare(ticks=1);

use \PAMI\Client\Impl\ClientImpl;
use \PAMI\Message\Event\EventMessage;
use \classes\Config;
use \classes\Log;

error_reporting(E_ALL);
ini_set('display_errors', 1);
Log::Info("main","Connector started");
while (true) {
    try {
        $options = Config::get('asterisk');
        $a = new ClientImpl($options);

        // Register DialBegin event
        $a->registerEventListener(
            new \events\DialBeginEvent(),
            function (EventMessage $event) {
                return $event instanceof \PAMI\Message\Event\DialEvent && $event->getSubEvent() == 'Begin';
            });

        // Register bridge event
        $a->registerEventListener(
            new \events\BridgeEvent(),
            function (EventMessage $event) {
                return $event instanceof \PAMI\Message\Event\BridgeEvent;
            });

        // Register cdr event
        $a->registerEventListener(
            new \events\CdrEvent(),
            function (EventMessage $event) {
                return $event->getName() == "Cdr";
            });

        // Register hangup event
        $a->registerEventListener(
            new \events\HangupEvent(),
            function (EventMessage $event) {
                return $event instanceof \PAMI\Message\Event\HangupEvent;
            });

        // Register DialEnd event
        $a->registerEventListener(
            new \events\DialEndEvent(),
            function ($event) {
                return $event instanceof \PAMI\Message\Event\DialEvent && $event->getSubEvent() == 'End';
            });

        // debug purposes
        $a->registerEventListener(new \events\BasicEvent());

        try{
            $a->open();
        }catch(Exception $ex){
            Log::Critical("main",print_r($ex,true),__FILE__,__LINE__);
            die("Cannot connect to Asterisk server");
        }

        $time = time();
        while (true)//(time() - $time) < 60) // Wait for events.
        {
            usleep(1000); // 1ms delay
            // Since we declare(ticks=1) at the top, the following line is not necessary

            $a->process();
        }
        $a->close();
    } catch (Exception $e) {
        Log::Critical("main",print_r($e,true),__FILE__,__LINE__);
    }
}
