<?php
// db.php - database connection for hotel_management
$host = "localhost";
$user = "root";
$pass = ""; // update if you have a MySQL password
$db = "hotel_management";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(["success"=>false, "error"=>"Connection failed: " . $conn->connect_error]));
}
?>
