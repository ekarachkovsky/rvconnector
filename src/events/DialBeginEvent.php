<?php
namespace events;

class DialBeginEvent extends BasicEvent
{
    public function handle(\PAMI\Message\Event\EventMessage $event)
    {
        //echo "===================Dial Begin!!!=====================\r\n";
        //var_dump($event);
        $caller = $event->getKey("calleridnum");
        $peer = $event->getKey("dialstring");
        $startdate = date('Y-m-d H:i:s',$event->getCreatedDate());

        echo "$caller calls to $peer\r\n";
        $db = new \classes\DBConnection();
        $channelVars = $event->getChannelVariables(lcfirst($event->getKey('channel')));
        if(array_key_exists('recordpath',$channelVars) && !empty($channelVars['recordpath'])){
            //echo "-----------------------------------------------\r\n";
            //echo "file = '".$channelVars['recordpath']."'\r\n";
            $fields['recordingpath']=$channelVars['recordpath'];
        }

        // Store our call in special db
        $sourceUid=$db->SaveCallStatus(
            $event->getKey('uniqueid'),array(
            'destuid'=>$event->getKey('destuniqueid'),
            'event'=>"DialBegin",
            'channel'=>$event->getKey('channel'),
            'caller'=>$caller,
            'peer'=>$peer,
            'starttime'=>$startdate
            ));

        // Add record to Vtiger about new call
        $this->callback(array(
            'callstatus'=>'DialBegin',
            'event'=>'DialBegin',
            'channel'=>$event->getKey('channel'),
            'callerIdNumber'=>$caller,
            'StartTime'=>$startdate,
            'dialString'=>$peer,
            'callUUID'=>$sourceUid
        ));
    }
}