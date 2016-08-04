<?php
require realpath(dirname(__FILE__).'/../vendor/autoload.php');

use \classes\Config;

if($_GET['secret'] != Config::get('Vtiger.VtigerSecretKey')){
    require "../index.php";
    die();
}

//TODO: implement outgoing call
//params: from = extension, to = phone number, context = outbound context (outcoming-sip)