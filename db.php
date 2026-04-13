<?php
$conn = mysqli_connect("localhost", "root", "123456", "cloth_factory");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
