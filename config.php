<?php
session_start();   

// db configuration variables
$serverName    =    "SANTHUS\SQLEXPRESS";
$database      =    "survery-form";
$uid           =    "";
$pwd           =    "";

// Connect to the local server using Windows Authentication.
$connectionInfo = array("Database" => $database, "Uid" => $uid, "PWD" => $pwd);
$conn           = sqlsrv_connect($serverName, $connectionInfo);

 

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}
  

?>
