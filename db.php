<?php
$host = "dessert-db.c24z8x36euba.us-east-1.rds.amazonaws.com";
$user = "admin";
$pass = "root1234";
$dbname = "DessertDB";

$port = 3306;

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
