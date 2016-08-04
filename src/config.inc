<?php

return array(
    // Asterisk manager config section
    'asterisk'=>array(
        'host' => '127.0.0.1',
        'scheme' => 'tcp://',
        'port' => 5038,
        'username' => 'admin',
        'secret' => 'secret',
        'connect_timeout' => 10,
        'read_timeout' => 10,
    ),
    // Logging
    'log'=>array(
        // Levels:
        //   DEBUG: Detailed debug information.
        //   INFO: Interesting events. Examples: User logs in, SQL logs.
        //   NOTICE: Normal but significant events.
        //   WARNING: Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
        //   ERROR: Runtime errors that do not require immediate action but should typically be logged and monitored.
        //   CRITICAL: Critical conditions. Example: Application component unavailable, unexpected exception.
        //   ALERT: Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
        //   EMERGENCY: Emergency: system is unusable.
        'level'=>'DEBUG',
        // Log file destination. Delimiter can be slash (/) only
        'destination'=>'logs/system.log'
    ),
    // Address where call records hosted. Note: port must be free and accessible from outside
    'recordsServer'=>'<asterisk_server_ip/name>:888888',
    // Vtiger options section
    'Vtiger'=>array(
        'VtigerURL' => 'http://vtigerhost/vtigerdir',
        'VtigerSecretKey' => '<secretkey from pbxmanager serttings>',
    ),
    // MySQL section
    'mysql'=>array(
        'host'=>'localhost',
        'dbname'=>'rvconnector',
        'login'=>'root',
        'pass'=>''
    ),
);