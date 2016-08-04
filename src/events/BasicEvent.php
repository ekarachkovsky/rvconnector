<?php
namespace events;


class BasicEvent implements \PAMI\Listener\IEventListener
{
    public function handle(\PAMI\Message\Event\EventMessage $event)
    {
        $eventName = lcfirst($event->getName());
        if (method_exists($event, 'getSubEvent')) {
            // If this event has a subevent string, then concatenate it to the
            // event name, like someSubEvent.
            $eventName .="----". $event->getSubEvent();
        }
        //var_dump($event);
        echo "$eventName\r\n";
    }

    protected function callback($params){
        // process vtiger calback
        $conn = new \classes\VTiger();
        $conn->Send($params);
    }
}