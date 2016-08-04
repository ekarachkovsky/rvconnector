<?php
namespace events;


class CdrEvent extends BasicEvent
{
    public function handle(\PAMI\Message\Event\EventMessage $event)
    {
//        echo "===================CDR=====================\r\n";
//        var_dump($event);

        $db = new \classes\DBConnection();

//         Store our call in special db
        $fields=array(
            'event'=>"CDR",
            'caller'=>$event->getKey('callerid'),
            'peer'=>$event->getKey('destination'),
            'starttime'=>$event->getKey('starttime'),
            'endtime'=>$event->getKey('endtime'),
            'totalduration'=>$event->getKey('duration'),
            'channel'=>$event->getKey('channel'),
        );
        $recordingPath = $event->getKey('recordingpath');
        if(!empty($recordingPath) || !$fields['totalduration']){
            $fields['recordingpath']=$recordingPath;
        }
        $recordId=$db->SaveCallStatus($event->getKey('uniqueid'),$fields,false);

        if(empty($recordingPath)){
            $recordingPath = $db->getRecordingPath($recordId);
        }

        if(!empty($recordingPath)) {
            $this->callback(array(
                'callstatus'=>'Record',
                'recordinglink'=>\classes\Config::getRecordUrl($recordId),
                'callUUID'=>$recordId,
            ));
        }
    }
}