<?php
// Database configuration (edit these values to match infinityfree settings) 


$host = "sql103.infinityfree.com";
$dbname = "if0_42305720_trade_crusaders";
$username = "if0_42305720";
$password = "ajenjYGcloyTZ";


$conn = new mysqli($host, $username, $password, $dbname);

if ($conn -> connect_error){
    die("Connection failed: " . $conn -> connect_error);

}
?>