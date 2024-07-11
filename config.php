<?php

function loadEnv($file) {
    if (file_exists($file)) {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Remove comments and trim whitespace
            $line = trim(preg_replace('/\s*#.*$/', '', $line));
            if ($line && strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                putenv(trim($name) . '=' . trim($value));
            }
        }
    }
}

loadEnv(__DIR__ . '/.env');

$serverName = getenv('DB_SERVER_NAME');
$database = getenv('DB_DATABASE');
$uid = getenv('DB_UID');
$pwd = getenv('DB_PWD');

//database connection
$connectionInfo = array("Database" => $database, "Uid" => $uid, "PWD" => $pwd);
$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}
 
session_start();   

  

?>
