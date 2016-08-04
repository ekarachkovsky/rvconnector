<?php
namespace events;


class DialEndEvent extends BasicEvent
{
    public function handle(\PAMI\Message\Event\EventMessage $event)
    {
        $db = new \classes\DBConnection();
        $uid = $event->getUniqueID();
        $record = $db->getCallStat($event->getUniqueID());
        if($record){
            $record['callstatus']='EndCall';
            $this->callback($record);
        }

    }
}