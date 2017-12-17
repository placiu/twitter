<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'coderslab');
define('DB_DBNAME', 'twitter');

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DBNAME . ";charset=utf8", DB_USER, DB_PASSWORD);
    //echo "connection ok";
} catch (PDOException $ex) {
    echo "Błąd połączenia: " . $ex->getMessage();
}