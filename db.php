<?php
$host = "localhost";
$user = "root";

$pass = "root123";

$dbname = "dessert_order_db";

$port = 3306;

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
