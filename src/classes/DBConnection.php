<?php

namespace classes;
use \classes\Log;

// support only mysql by now
class DBConnection
{
    private $db;

    function __construct()
    {
        try {
            $this->db=new \PDO('mysql:host=' . Config::get('mysql.host') . ';dbname=' . Config::get('mysql.dbname'), Config::get('mysql.login'), Config::get('mysql.pass'),array(\PDO::ATTR_PERSISTENT=>true));
            Log::Debug(__CLASS__,'Connected to DB '.Config::get('mysql.host') . ':' . Config::get('mysql.dbname'));
        }catch(\PDOException $ex){
            // If database does not exists, we'll create it
            if($ex->getCode()==1049){
                $this->seedDb();
            }else{
                throw $ex;
            }
        }
    }

    private function execute($sql,$params = false){
        Log::Debug(__CLASS__,"Prepared SQL statement: $sql");
        Log::Debug(__CLASS__,"Parameters: ".print_r($params,true));
        $stmt = $this->db->prepare($sql);
        try {
            if ($params) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
        }catch(\Exception $ex){
            Log::Error(__CLASS__,"Error while processing SQL statement: <$sql> with parameters <".print_r($params).">",__FILE__,__LINE__);
            throw $ex;
        }
        return $stmt;
    }

    public function SaveCallStatus($srcuid, $fields,$create=true){
        $parameters = array();
        $recordId=$this->getCallUid($srcuid);

        if($recordId) {
            $sql = "UPDATE calls set ";
            $setPart="";
            foreach($fields as $fieldName=>$fieldValue){
                $setPart .= ",$fieldName = ?";
                $parameters[]=$fieldValue;
            }
            $sql .= substr($setPart,1)." where id = ?";
            $parameters[]=$recordId;
            $this->execute($sql,$parameters);
        }elseif($create){
            $sql = "INSERT INTO calls (srcuid";
            $parameters[]=$srcuid;
            $qmarks = "?";
            foreach($fields as $fieldName=>$fieldValue){
                $sql .= ",$fieldName";
                $qmarks .= ",?";
                $parameters[]=$fieldValue;
            }
            $sql .= ") VALUES($qmarks)";
            $this->execute($sql,$parameters);
            $recordId=$this->getCallUid($srcuid);
        }

        return $recordId;
    }

    public function getCallUid($uniqueId){
        $qry=$this->execute("select id from calls where srcuid=? or destuid=?",array($uniqueId,$uniqueId));
        $callId = $qry->fetchColumn(0);

        return $callId;
    }
    public function getRecordingPath($recordId){
        $qry=$this->execute("select recordingpath from calls where id=?",array($recordId));
        $path = $qry->fetchColumn();
        return $path;
    }

    public function getCallStat($uniqueId){
        $qry=$this->execute(
            "SELECT id as `callUUID`,starttime,endtime,totalduration as duration,totalduration as billableseconds FROM rvconnector.calls where srcuid=?"
            ,array($uniqueId));
        $callId = $qry->fetch(\PDO::FETCH_ASSOC);

        return $callId;
    }

    public function checkHangupCause($recordId,$cause){
        $qry = $this->execute("select bridged from calls where id=?",array($recordId));
        $bridged = $qry->fetchColumn();

        if(empty($bridged)){
            $cause="NO ANSWER";
        }

        return $cause;
    }

    private function seedDB(){
        Log::Info(__CLASS__,"Database not found, try to create new DB");
        try {
            $tdb = new \PDO('mysql:host=' . Config::get('mysql.host') . ';dbname=INFORMATION_SCHEMA', Config::get('mysql.login'), Config::get('mysql.pass'), array(\PDO::ATTR_PERSISTENT => true));
            $tdb->exec("CREATE DATABASE `" . Config::get('mysql.dbname') . "`");
            $tdb = null;
            $this->db = new \PDO('mysql:host=' . Config::get('mysql.host') . ';dbname=' . Config::get('mysql.dbname'), Config::get('mysql.login'), Config::get('mysql.pass'), array(\PDO::ATTR_PERSISTENT => true));
            $this->execute("
CREATE TABLE calls (
	 id int(11) NOT NULL AUTO_INCREMENT
	,srcuid VARCHAR(255) NOT NULL
	,destuid VARCHAR(255) DEFAULT NULL
	,event VARCHAR(50) DEFAULT NULL
	,direction VARCHAR(50) DEFAULT NULL
	,channel VARCHAR(255) DEFAULT NULL
	,caller VARCHAR(255) DEFAULT NULL
	,peer VARCHAR(255) DEFAULT NULL
	,starttime DATETIME DEFAULT NULL
	,endtime DATETIME DEFAULT NULL
	,totalduration INT DEFAULT NULL
	,bridged VARCHAR(20) DEFAULT NULL
	,callcause VARCHAR(50) DEFAULT NULL
	,recordingpath VARCHAR(255) DEFAULT NULL
	,recordingurl VARCHAR(255) DEFAULT NULL
	,PRIMARY KEY (id)
	)");
            $this->execute("CREATE INDEX idx_srcuid on calls(srcuid) USING BTREE");
            $this->execute("CREATE INDEX idx_destuid on calls(destuid) USING BTREE");
        }catch(\Exception $ex){
            Log::Critical(__CLASS__,"Error while database creation: ".print_r($ex,true),__FILE__,__LINE__);
        }
    }
}