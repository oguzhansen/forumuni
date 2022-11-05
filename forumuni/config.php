<?php
$host     = "localhost"; // Database Host
$user     = "xcuforum"; // Database Username
$password = "X[ZT{Kj9"; // Database's user Password
$database = "xcuforum_unifor"; // Database Name

$connect = new mysqli($host, $user, $password, $database);

// Checking Connection
if (mysqli_connect_errno()) {
    printf("Database connection failed: %s\n", mysqli_connect_error());
    exit();
}

mysqli_set_charset($connect, "utf8mb4");

$site_url = "http://forumuni.com";
?>