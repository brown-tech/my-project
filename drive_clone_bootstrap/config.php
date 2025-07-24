<?php
$conn = new mysqli("localhost", "root", "", "drive");
session_start();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>