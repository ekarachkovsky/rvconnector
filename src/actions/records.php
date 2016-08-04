<?php
require realpath(dirname(__FILE__).'/../vendor/autoload.php');
use \classes\DBConnection;

$id = $_GET['id']*1;

$db = new DBConnection();

$file = $db->getRecordingPath($id);
if(empty($file) || !file_exists($file)){
    echo "File not found or no permissions to read file. Report to your system administrator";
}else{
    $fname=basename($file);
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.$fname.'"');
    header('Content-Length: '.filesize($file));
    header("Content-Transfer-Encoding: Binary");
    readfile($file);
}