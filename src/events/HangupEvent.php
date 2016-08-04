<?php
namespace events;


class HangupEvent extends BasicEvent
{
    public function handle(\PAMI\Message\Event\EventMessage $event)
    {
        $db = new \classes\DBConnection();

        $cause = $event->getCauseText();
        $record=$db->SaveCallStatus($event->getUniqueID(),array(
            'callcause'=>$cause,
            'event'=>'Hangup'
        ),false);

        if($record){
            // if there was no bridge, then no answer
            if($cause=="Normal Clearing"){
                $cause=$db->checkHangupCause($record,$cause);
            }
            $this->callback(array(
                'callUUID'=>$record,
                'causetxt'=>$cause,
                'callstatus'=>'Hangup',
                'callerIdNum'=>$event->getCallerIDNum(),
            ));
        }

    }
}