<?php
namespace events;


class BridgeEvent extends BasicEvent
{
    public function handle(\PAMI\Message\Event\EventMessage $event)
    {
//        echo "===================Bridge=====================\r\n";
//        var_dump($event);
        $caller = $event->getCallerID1();
        $peer = $event->getCallerID2();

        $db = new \classes\DBConnection();
        $channelVars = $event->getChannelVariables(lcfirst($event->getKey('channel1')));

        $fields = array(
            'caller'=>$caller,
            'peer'=>$peer,
            'event'=>'Bridge',
            'bridged'=>$event->getBridgeState()
        );
        var_dump($channelVars);
        if($event->getBridgeState()=='Link' && array_key_exists('recordpath',$channelVars) && !empty($channelVars['recordpath'])){
            echo "-----------------------------------------------\r\n";
            echo "file = '".$channelVars['recordpath']."'\r\n";
            $fields['recordingpath']=$channelVars['recordpath'];
        }

        // Store our call in special db
        $sourceUid=$db->SaveCallStatus($event->getKey('uniqueid1'),$fields,false);

        if($event->getBridgeState()=='Link'){
            if($sourceUid){
                $this->callback(array(
                    'callstatus'=>'DialAnswer',
                    'uniqueid1'=>$event->getUniqueID1(),
                    'uniqueid2'=>$event->getUniqueID2(),
                    'callerid1'=>$caller,
                    'callerid2'=>$peer,
                    'dateReceived'=>date('Y-m-d H:i:s',$event->getCreatedDate()),
                    'callUUID'=>$sourceUid,
                ));
            }
        }
    }
}