<?php
$sname= "127.0.0.1";
$unmae= "www-aplikace";
$password = "Bezpe4n0Heslo.";

$db_name = "ip_3";

$conn = mysqli_connect($sname, $unmae, $password, $db_name);

if (!$conn) {
    echo "Connection failed!";
}