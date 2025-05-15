<?php


$servername = "localhost";
$username = "root";
$password = "";
$database = "tokobuku";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Gagal Bos") . mysqli_connect_error();
}
