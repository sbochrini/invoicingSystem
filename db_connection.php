<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "db_invoicing_system";

// Create connection
$connection = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}