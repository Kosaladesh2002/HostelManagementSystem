<?php
$host = "localhost";
$user = "root";  
$password = ""; 
$dbname = "hostel_management";


// Remove the port if it's the default (3306)
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
